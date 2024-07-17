<?php

namespace App\Http\Controllers\Api;

use App\Models\Staff;
use Illuminate\Http\Request;
use App\Rules\ValidateEmail;
use App\Rules\ValidatePassword;
use App\Http\Requests\Api\Auth\ChangePasswordRequest;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Auth\TokenCheckRequest;
use App\Http\Resources\Staff\Staff as StaffResource;
use App\Http\Resources\Consumer\Consumer as ConsumerResource;
use App\Models\Consumer;
use App\Models\ConsumerCard;
use App\Models\User;
use Auth;
use Google_Client;
use Carbon\Carbon;
use Hash;
use Illuminate\Validation\ValidationException;
use JWTAuth;
use Socialite;
use Validator;

/**
 * @group Auth
 *
 * APIs for managing user authencatication related activities
 */
class AuthController extends BaseController
{
    protected $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->user = new User();
    }

    /**
     * Login consumer
     * Authenticate a user by email.
     *
     * @bodyParam email string required The email of the user. Example: abc@example.com
     * @bodyParam password string required The password of the user. Example: 123456
     */
    public function authenticateConsumer(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $credentials['is_verified'] = true;
        $credentials['type'] = 'Consumer';

        $token = JWTAuth::attempt($credentials);
        $user = Auth::user();

        if (!$token) {
            throw new \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException('Basic', 'Invalid credentials.');
        }

        $consumer = Consumer::where('user_id', $user->id)->first();

        return (new ConsumerResource($consumer))
                ->additional(['meta' => [
                    'token' => $token,
                ]]);
    }

    /**
     * Login staff user
     * Authenticate a user by email.
     *
     * @bodyParam email string required The email of the user. Example: abc@example.com
     * @bodyParam password string required The password of the user. Example: 123456
     */
    public function authenticateStaff(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $credentials['is_verified'] = true;
        $credentials['type'] = 'Staff';

        $token = JWTAuth::attempt($credentials);
        $user = Auth::user();

        if (!$token) {
            throw new \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException('Basic', 'Invalid credentials.');
        }

        $staff = Staff::where('user_id', $user->id)->first();

        return (new StaffResource($staff))
                ->additional(['meta' => [
                    'token' => $token,
                ]]);
    }

    /**
     * Logout staff user
     * Logout authenticated staff user.
     */
    public function staffLogout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json([
            'message' => 'Logout has been done successfully.',
        ]);
    }

    /**
     * Register consumer
     * Register a consumer by email.
     *
     * @bodyParam email string required The email of the user. Example: abc@example.com
     * @bodyParam password string required The password of the user, is required in case provider is as 'facebook' or 'google'. Example: 123456
     * @bodyParam first_name string required The first name of the user. Example: Bill
     * @bodyParam last_name string required The last name of the user. Example: Gates
     * @bodyParam receive_offers boolean required Whether user like to receive offers. Example: true
     * @bodyParam date_of_birth string required The password of the user. Example: 1993/06/24
     * @bodyParam timezone string required Whether user like to receive offers. Example: Asia/Kolkata
     * @bodyParam provider string required It can be 'email', facebook' or 'google'. Example: facebook
     * @bodyParam provider_id string It is a string that is used identify user on social tool, is required if provider is as 'google' or 'facebook'. Example: abcd
     * @bodyParam card_id integer It is a integer value. Example: 1
     */
    public function registerConsumer(RegisterRequest $request)
    {
        $user = User::create([
            'first_name'  => $request['first_name'],
            'last_name'   => $request['last_name'],
            'email'       => $request['email'],
            'type'        => 'Consumer',
            'password'    => isset($request['password']) ? bcrypt($request['password']) : null,
            'provider'    => $request['provider'],
            'provider_id' => isset($request['provider_id']) ? $request['provider_id'] : null,
            'is_verified' => true,
        ])->assignRole('consumer');

        if (!$user) {
            throw new \Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException('Could not create new user.');
        }

        $consumer = Consumer::create([
            'receive_offers' => $request['receive_offers'] == 'true' ? true : false,
            'date_of_birth'  => Carbon::createFromFormat(config('fanslive.DATE_APP_REQUEST_FORMAT'), $request['date_of_birth'])->format('Y-m-d'),
            'time_zone'      => $request['timezone'],
            'user_id'        => $user->id,
        ]);

        if ($request['provider'] === 'email' && isset($request['card_id'])) {
            $consumerCard = ConsumerCard::find($request['card_id']);
            if ($consumerCard && $consumerCard->consumer_id === null) {
                $consumerCard->consumer_id = $consumer->id;
                $consumerCard->save();
            }
        }

        $token = JWTAuth::fromUser($user);

        if (!$token) {
            throw new \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException('Basic', 'Invalid credentials.');
        }

        return (new ConsumerResource($consumer))
                ->additional(['meta' => [
                    'token' => $token,
                ]]);
    }

    public function passwordSet(PasswordSetRequest $request, User $user)
    {
        $status = $this->registerService->checkEmailToken($user, $request->token);

        if (!$status) {
            throw new \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException('Basic', 'Invalid password');
        }

        $password = $request->password;
        $user->password = Hash::make($password);
        $user->is_verified = true;
        $user->save();
        $user->verifyUser->delete();

        $credentials = ['email' => $user->email, 'password' => $password];
        $token = JWTAuth::attempt($credentials);
        $user = Auth::user();

        if (!$token) {
            throw new \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException('Basic', 'Invalid credentials.');
        }

        return $this->response->item($user, new UserTransformer())
            ->setMeta([
                'token' => $token,
                'roles' => $user->roles->pluck('name'),
            ]);
    }

    /**
     * Social login
     * Authenticate a user by social login.
     *
     * @bodyParam token string required The token of the user.
     * @bodyParam provider string required It should be 'facebook' or 'google'. Example: facebook
     * @bodyParam user_identifier int required It should be any integer. Example: 1
     */
    public function socialLogin(TokenCheckRequest $request)
    {
        $token = $request->token;
        $provider = $request->provider;

        try {
            switch ($provider) {
                case 'apple':
                    $user = [];
                    $user['id'] = $request->user_identifier;
                    $user['email'] = $request->email;
                    $user['first_name'] = $request->first_name;
                    $user['last_name'] = $request->last_name;
                    break;
                case 'google':
                    $client = new Google_Client(['client_id' => config('services.google.client_id')]);
                    $payload = $client->verifyIdToken($token);
                    if ($payload) {
                        $user = $this->getGoogleUserData($payload);
                    } else {
                        throw new \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException('Basic', 'Incorrect token');
                    }
                    break;
                case 'facebook':
                    Socialite::driver($provider)->fields(['name', 'first_name', 'last_name', 'email']);
                    $payload = Socialite::driver($provider)->userFromToken($token);
                    $user = $this->getFacebookUserData($payload);
                    break;
                default:
                    $user = Socialite::driver($provider)->userFromToken($token);
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            throw new \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException('Basic', $e->getMessage());
        }

        $authUser = User::where('provider_id', $user['id'])->first();

        if (!$authUser) {
            if (isset($user['email'])) {
                $validator = Validator::make(['email' => $user['email']], [
                    'email' => 'required|email|unique:users,email',
                ]);

                if ($validator->fails()) {
                    throw new ValidationException($validator);
                }
            }

            $validator = Validator::make($user, [
                'email'          => 'required',
                'first_name'     => 'required',
                'last_name'      => 'required',
                'date_of_birth'  => 'required',
                'receive_offers' => 'required',
            ]);

            $provideResponse = [];
            if (isset($user['first_name'])) {
                $provideResponse['first_name'] = $user['first_name'];
            }

            if (isset($user['last_name'])) {
                $provideResponse['last_name'] = $user['last_name'];
            }

            if (isset($user['email'])) {
                $provideResponse['email'] = $user['email'];
            }

            $responseData = [
                'errors'            => $validator->errors(),
                'provider'          => $provider,
                'provider_id'       => $user['id'],
                'provider_response' => $provideResponse,
            ];

            if ($validator->fails()) {
                return response($responseData, 200);
            }
        }

        $token = JWTAuth::fromUser($authUser);

        $consumer = Consumer::where('user_id', $authUser->id)->first();

        if (!$token) {
            throw new \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException('Basic', 'Invalid credentials.');
        }

        return (new ConsumerResource($consumer))
                ->additional(['meta' => [
                    'token' => $token,
                ]]);
    }

    public function isEmailInUse($email)
    {
        $count = User::where('email', $email)->count();

        return $count > 0;
    }

    /**
     * Get user data from Google provider.
     */
    protected function getGoogleUserData(array $user)
    {
        $googleUserDetail = [
            'id'    => $user['sub'],
            'email' => $user['email'],
        ];
        $userName = isset($user['name']) ? explode(' ', $user['name'], 2) : null;
        $googleUserDetail['first_name'] = isset($user['given_name']) ? $user['given_name'] : (isset($userName[0]) ? $userName[0] : null);
        $googleUserDetail['last_name'] = isset($user['family_name']) ? $user['family_name'] : (isset($userName[1]) ? $userName[1] : null);

        return $googleUserDetail;
    }

    /**
     * Get user data from Facebook provider.
     */
    protected function getFacebookUserData($user)
    {
        $facebookUserDetail = [
            'id'    => $user->id,
            'email' => isset($user->user['email']) ? $user->user['email'] : null,
        ];
        $userName = isset($user->user['name']) ? explode(' ', $user->user['name'], 2) : null;
        $facebookUserDetail['first_name'] = isset($user['first_name']) ? $user['first_name'] : (isset($userName[0]) ? $userName[0] : null);
        $facebookUserDetail['last_name'] = isset($user['last_name']) ? $user['last_name'] : (isset($userName[1]) ? $userName[1] : null);

        return $facebookUserDetail;
    }

    /**
     * Logout
     * Logout authenticated user.
     */
    public function logout()
    {
        $user = JWTAuth::user();
        $user->device_token = null;
        $user->save();

        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json([
            'message' => 'Logout has been done successfully.',
        ]);
    }

    /**
     * Change password
     * Change user's password.
     *
     * @bodyParam password string required The password of a user. Example: 123456
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $password = $request['password'];
        $user = JWTAuth::user();
        $user->password = Hash::make($password);
        if ($user->save()) {
            return response()->json([
                'message' => 'Password updated successfully.',
            ]);
        }
    }

    /**
     * Change staff password
     * Change staff user's password.
     *
     * @bodyParam password string required The password of a user. Example: 123456
     */
    public function changeStaffUserPassword(ChangePasswordRequest $request)
    {
        $password = $request['password'];
        $user = JWTAuth::user();
        $user->password = Hash::make($password);
        if ($user->save()) {
            return response()->json([
                'message' => 'Password updated successfully.',
            ]);
        }
    }

    /**
     * Validate password
     * Validate consumer password.
     *
     * @bodyParam email string required The email of the user, is required if provider is other than email. Example: abc@example.com
     * @bodyParam password string required The password of the user, is required if provider is email. Example: password
     */
    public function validatePassword(Request $request)
    {
        $request = $request->all();
        $user = JWTAuth::user();
        $consumer = Consumer::where('user_id', $user->id)->first();

        if (!$consumer) {
            return response()->json([
                'message' => 'No consumer found.'
            ], 404);
        }

        if ($user->provider === 'email') {
            $validator = Validator::make($request, [
                'password' => [
                    'bail',
                    'required',
                    'min:8',
                    new ValidatePassword($user->password),
                ],
            ]);
        }

        if ($user->provider !== 'email') {
            $validator = Validator::make($request, [
                'email' => [
                    'bail',
                    'required',
                    new ValidateEmail($user->email),
                ],
            ]);
        }

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Entered password is incorrect.'
            ], 401);
        }
        
        return response()->json([
            'message' => 'Entered password is correct.',
        ]);
    }

    /**
     * Validate email
     * Validate email.
     *
     * @bodyParam email string required The email of the user. Example: abc@example.com
     * @bodyParam type string required The type of the user, it can be 'Consumer' or 'Staff' Example: Consumer
     */
    public function validateEmail(Request $request)
    {
        $validator = Validator::make($request->all(), 
            [
                'email' => 'required|email',
                'type' => 'required',
            ],
            [
                'email.required' => 'The email field is required.',
                'email.email' => 'Please enter valid email.',
                'type.required' => 'The type field is required.',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $checkEmailExists = User::where(['email' => $request->email, 'type' => $request->type])->count();
        if ($checkEmailExists == 0) {
            return response()->json([
                'status' => 'success',
            ]);
        } else {
            return response()->json([
                'message' => 'The email is already in use.',
            ], 409);
        }
    }
}
