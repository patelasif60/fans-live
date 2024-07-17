<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CMS\SetPasswordRequest;
use App\Models\User;
use App\Models\VerifyUser;
use App\Providers\RouteServiceProvider;
use Auth;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    protected $redirectLogin = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Handle setPassword logic.
     *
     * @param array $request
     * @param  $token
     */
    public function setPassword(Request $request, $token)
    {
        $email = $request->email;
        $verifyUser = VerifyUser::where('token', $token)->first();
        if (!$verifyUser) {
            return redirect($this->redirectLogin)->with('status', 'This link has expired');
        }

        return view('backend.users.cms.set_password', compact('token', 'email'));
    }

    /**
     * Handle savePassword logic.
     *
     * @param array $request
     */
    public function savePassword(SetPasswordRequest $request)
    {
        $token = $request->token;
        $verifyUser = VerifyUser::where('token', $token)->first();
        if ($verifyUser) {
            if ($verifyUser->user->is_verified) {
                return redirect($this->redirectLogin)->with('status', 'Your email is already verified. You can now login.');
            }
            $password = $request->password;
            $verifyUser->user->password = Hash::make($password);
            $verifyUser->user->is_verified = true;
            $verifyUser->user->save();
            $verifyUser->delete();
            Auth::attempt(['email' => $verifyUser->user->email, 'password' => $password]);
        }

        return redirect($this->redirectLogin);
    }
}
