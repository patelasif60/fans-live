<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Consumer\GetProfileRequest;
use App\Http\Requests\Api\Consumer\UpdateRequest;
use App\Http\Resources\Consumer\Consumer as ConsumerResource;
use App\Models\Consumer;
use App\Rules\ValidateEmail;
use App\Rules\ValidatePassword;
use App\Services\ConsumerService;
use Illuminate\Http\Request;
use JWTAuth;
use Validator;

/**
 * @group Consumer
 *
 * APIs for Consumer.
 */
class ConsumerController extends BaseController
{
    /**
     * A consumer service variable.
     *
     * @var service
     */
    protected $service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ConsumerService $service)
    {
        $this->service = $service;
    }

    /**
     * Destory/Unset object variables.
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->service);
    }

    /**
     * Update profile
     * Update consumer profile details.
     *
     * @bodyParam email string required The email of the user. Example: abc@example.com
     * @bodyParam first_name string required The first name of the user. Example: Bill
     * @bodyParam last_name string required The last name of the user. Example: Gates
     * @bodyParam date_of_birth string required A date of birth of the user. Example: 25/12/1992
     * @bodyParam receive_offers boolean required Whether user like to receive offers. Example: true
     * @bodyParam timezone string required Whether user like to receive offers. Example: Asia/Kolkata
     */
    public function updateProfile(UpdateRequest $request)
    {
        $user = JWTAuth::user();
        $consumer = $this->service->updateConsumer(
            $user,
            $request->all()
        );

        if ($consumer) {
            return response()->json([
                'message' => 'Profile updated successfully.',
            ]);
        }
    }

    /**
     * Delete account
     * Delete consumer account.
     *
     * @bodyParam email string required The email of the user, is required if provider is other than email. Example: abc@example.com
     * @bodyParam password string required The password of the user, is required if provider is email. Example: password
     */
    public function deleteAccount(Request $request)
    {
        $request = $request->all();
        $user = JWTAuth::user();
        $consumer = Consumer::where('user_id', $user->id)->first();

        if (!$consumer) {
            return response(['errors' => ['No consumer found.']], 401);
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
            return response(['errors' => $validator->errors()], 401);
        }

        $consumer = $this->service->deleteConsumer(
            $user->id
        );

        if ($consumer) {
            return response()->json([
                'message' => 'Consumer deleted successfully.',
            ]);
        }
    }

    /**
     * Get profile
     * Get consumer profile details.
     *
     * @bodyParam id int required An id of the user. Example: 1
     */
    public function getProfile()
    {
        $user = JWTAuth::user();
        $profile = Consumer::where('user_id', $user->id)->first();

        return new ConsumerResource($profile);
    }

    /**
     * Update settings
     * Update consumer profile details.
     *
     * @bodyParam settings json required The keys and values of a user settings. Example: {is_notification_enabled: true}
     */
    public function updateSettings(Request $request)
    {
        $user = JWTAuth::user();
        $consumer = $this->service->updateSettings(
            $user,
            $request->all()
        );
        if ($consumer) {
            return response()->json([
                'message' => 'Settings updated successfully.',
            ]);
        }
    }
}
