<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\PasswordResetSetRequest;
use App\Providers\RouteServiceProvider;
use Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

/**
 * @group Auth
 *
 * APIs for managing user authencatication related activities
 */
class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

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
     * Reset password
     * Reset the given user's password.
     *
     * @bodyParam email string required The email of the user. Example: abc@example.com
     * @bodyParam password string required The new password of the user. Example: 123456
     * @bodyParam password_confirmation string required The password confirmation of the user, should be same as password. Example: 123456
     * @bodyParam token string required The token sent in reset password link.
     *
     * @param PasswordResetSetRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function reset(PasswordResetSetRequest $request)
    {
        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset(
            $this->credentials($request),
            function ($user, $password) {
                // $this->resetPassword($user, $password);
                $user->password = Hash::make($password);
                $user->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $response == Password::PASSWORD_RESET
                    ? $this->sendResetResponse($request, $response)
                    : $this->sendResetFailedResponse($request, $response);
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param string $response
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetResponse(PasswordResetSetRequest $request, $response)
    {
        if ($request->wantsJson()) {
            return response()->json(['message' => trans($response)]);
        }

        return redirect($this->redirectPath())
                            ->with('status', trans($response));
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param PasswordResetSetRequest $request
     * @param string                  $response
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetFailedResponse(PasswordResetSetRequest $request, $response)
    {
        if ($request->wantsJson()) {
            return response()->json(['message' => trans($response)], 401);
        }

        return redirect()->back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => trans($response)]);
    }
}
