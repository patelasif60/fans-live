<?php

namespace App\Http\Middleware;

use App;
use App\Models\Club;
use App\Models\CMS;
use Auth;
use Closure;
use JavaScript;
use View;

class CheckCMSPanel
{
    /**
     * @var Current panel
     */
    protected $currentPanel;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->currentPanel = 'superadmin';
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $club = $request->route('club');
        if ($club) {
            $this->currentPanel = 'clubadmin';
            $clubDetail = Club::where('slug', $club)->first();
            if (!$clubDetail) {
                App::abort(404);
            }
            if (!Auth::user()->hasRole('superadmin')) {
                $cmsUserDetail = CMS::where('user_id', Auth::user()->id)->first();
                if (!$cmsUserDetail->hasClubAccess($clubDetail->id)) {
                    App::abort(403);
                }
            }
            $request->request->add(['global_club_timezone' => $clubDetail->time_zone]);
            View::share('clubDetail', $clubDetail);
            JavaScript::put([
                'clubTimezone' => $clubDetail->time_zone,
            ]);
        }

        View::share('currentPanel', $this->currentPanel);

        View::share('dateTimeCmsFormat', config('fanslive.DATE_TIME_CMS_FORMAT.php'));
        View::share('dateCmsFormat', config('fanslive.DATE_CMS_FORMAT.php'));

        JavaScript::put([
            'dateTimeCmsFormat' => config('fanslive.DATE_TIME_CMS_FORMAT.js'),
            'dateCmsFormat'     => config('fanslive.DATE_CMS_FORMAT.js'),
            'currentPanel'      => $this->currentPanel,
        ]);

        return $next($request);
    }
}
