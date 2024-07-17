<?php

namespace App\Http\Controllers\Auth;

use App;
use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\CMS;
use App\Models\User;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Handle a login request to the application.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @throws \Illuminate\Validation\ValidationException
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $user = User::where('email', $request->email)->first();
        if (Auth::validate(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::getLastAttempted();
            if ($user->type != 'Superadmin' && $user->type != 'Clubadmin') {
                 return $this->sendFailedLoginResponse($request);
                //return App::abort(401, 'Unauthorized');
            }
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Log the user out of the application.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect()->route('login');
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    public function redirectTo()
    {
        $user = Auth::user();
        $permission = (Auth::user()->getAllPermissions()->pluck('name')->toArray());
        $config = config('fanslive.CLUB_PERMISSIONS_URL');
        // Check user role
        if ($user->type == 'Superadmin') {
            return route('backend.superadmin.dashboard');
        } elseif ($user->type == 'Clubadmin') {
            $cmsUserDetail = CMS::where('user_id', $user->id)->first();
            if ($cmsUserDetail->club_id) {
                $club = Club::find($cmsUserDetail->club_id);
                if(in_array("access.clubadmin.dashboard.own", \Auth::user()->getAllPermissions()->pluck('name')->toArray())){
                    return route('backend.clubadmin.dashboard', ['club' => $club->slug]);
                }
                return route($config[$permission[0]],['club' => $club->slug]);
            }
            App::abort(400, 'Default club is not set.');
        }
    }

    /**
     * Redirect the user to the OAuth Provider.
     *
     * @param $provider
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        $type = $_COOKIE['user-type-for-registration'] ?? null;
        session(['user-type-for-registration' => $type]);

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from provider. Check if the user already exists in our
     * database by looking up their provider_id in the database.
     *
     * If the user exists, log them in. Otherwise, create a new user then log them in. After that
     * redirect them to the authenticated users homepage.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();

        $authUser = $this->findOrCreateUser($user, $provider);
        if (!$authUser) {
            return redirect('/login')->with('status', 'The email has already been taken.');
        }

        Auth::login($authUser, true);

        if ($authUser->wasRecentlyCreated) {
            Mail::to($authUser)->send(new WelcomeEmailNoVerification($authUser));

            return $this->redirectToAfterSocialRegistration();
        }

        return redirect($this->redirectTo());
    }
}
