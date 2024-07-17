<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\PasswordResetRequest;
use Carbon\Carbon;
use DB;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Auth
 *
 * APIs for managing user authencatication related activities
 */
class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

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
     * Reset password email
     * Send a reset link to the given user.
     *
     * @bodyParam email string required The email of the user. Example: abc@example.com
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(PasswordResetRequest $request)
    {
        //Password reset time check
        $tries = 0;
        $passwordReset = DB::table('password_resets')
                ->where('email', $request['email'])
                ->first();
        if ($passwordReset) {
            $tries = $passwordReset->tries;
            $diffInMinutes = Carbon::now()->diffInMinutes(Carbon::parse($passwordReset->last_requested_at));
        }

        if ($tries == 3) {
            $reset_password_interval = config('fanslive.RESET_PASSWORD_INTERVAL');

            if ($diffInMinutes > $reset_password_interval) {
                $tries = 0;
                $passwordReset = DB::table('password_resets')
                                    ->where('email', $request['email'])
                                    ->update(['tries' => $tries]);
            } else {
                $hourDuration = ($reset_password_interval / 60);
                $msg = 'Too many reset password attempts. Please try again after '.$hourDuration.' hour.';

                if ($request->wantsJson()) {
                    return response()->json([
                        'status'  => '401',
                        'message' => $msg,
                    ], Response::HTTP_UNAUTHORIZED);
                }

                return back()->with('status', trans($msg));
            }
        }
        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        // saving tries and last_request_at
        DB::table('password_resets')
                ->where('email', $request['email'])
                ->update(['tries' => $tries + 1, 'last_requested_at' => Carbon::now()]);

        return $response == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkResponse($request, $response)
                    : $this->sendResetLinkFailedResponse($request, $response);
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param string $response
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        if ($request->wantsJson()) {
            return response()->json(['message' => trans($response)]);
        }

        return back()->with('status', trans($response));
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param  \Illuminate\Http\Request
     * @param string $response
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        if ($request->wantsJson()) {
            return response()->json(['message' => trans($response)], 422);
        }

        return back()->withErrors(
            ['email' => trans($response)]
        );
    }
}
