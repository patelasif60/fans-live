<?php

namespace App\Http\Middleware;

use App;
use App\Models\Club;
use App\Models\CMS;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string|null              $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $user = Auth::user();
            if ($user->type == 'Superadmin') {
                return redirect()->route('backend.superadmin.dashboard');
            } elseif ($user->type == 'Clubadmin') {
                $cmsUserDetail = CMS::where('user_id', $user->id)->first();
                if ($cmsUserDetail->club_id) {
                    $club = Club::find($cmsUserDetail->club_id);

                    return redirect()->route('backend.clubadmin.dashboard', ['club' => $club->slug]);
                }
                App::abort(400, 'Default club is not set.');
            }
        }

        return $next($request);
    }
}
