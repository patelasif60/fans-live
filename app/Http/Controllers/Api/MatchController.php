<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Match\GetMatchDetailsRequest;
use App\Http\Resources\Match\Match as MatchResource;
use App\Models\Consumer;
use App\Models\Match;
use App\Services\MatchService;
use Illuminate\Http\Request;
use JWTAuth;

/**
 * @group Match
 *
 * APIs for Match.
 */
class MatchController extends BaseController
{
    /**
     * Create a match service variable.
     *
     * @return void
     */
    protected $service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(MatchService $service)
    {
        $this->service = $service;
    }

    /**
     * Get fixtures list
     * Get fixtures list and results.
     *
     * @return \Illuminate\Http\Response
     */
    public function getFixturesList(Request $request)
    {
        $fixtures = Match::all();

        return MatchResource::collection($fixtures);
    }

    /**
     * Get match details
     * Get match details.
     *
     * @bodyParam id int required An id of a match. Example: 1
     *
     * @return \Illuminate\Http\Response
     */
    public function getMatchDetails(GetMatchDetailsRequest $request)
    {
        $match = Match::find($request['id']);

        return new MatchResource($match);
    }

    /**
     * Get match details of an in progress match
     * Get match details of an in progress match.
     *
     * @bodyParam id int required An id of a match. Example: 1
     *
     * @return \Illuminate\Http\Response
     */
    public function getInProgressMatchDetails(GetMatchDetailsRequest $request)
    {
        $match = Match::find($request['id']);
        if ($match->status == 'in_play') {
            return new MatchResource($match);
        } else {
            return response()->json([
                'message' => 'Currently, this match is not in progress.',
            ]);
        }
    }

    /**
     * Get close to real time events
     * Get close to real time events.
     *
     * @bodyParam id int required An id of a match. Example: 1
     *
     * @return \Illuminate\Http\Response
     */
    public function getCloseToRealTimeEvents(Request $request)
    {
    }

    /**
     * Get an upcoming match list.
     *
     *@bodyParam id int required An id of home team or away team. Example: 1
     *
     * @return \Illuminate\Http\Response
     */
    public function getUpcomingMatch(Request $request)
    {
        $user = JWTAuth::user();
        $consumer = Consumer::where('user_id', $user->id)->first();
        $clubId = $consumer->club_id;
        $match = Match::where(function ($query) {
            $query->where('status', 'scheduled')
                    ->orWhere('status', 'in_play')
                    ->orWhere('status', 'paused');
        })
        ->where(function ($query) use ($clubId) {
            $query->where('home_team_id', $clubId)
            ->orWhere('away_team_id', $clubId);
        })->orderBy('kickoff_time', 'asc')->get();

        return MatchResource::collection($match);
    }

    /**
     * Get a finished match list.
     *
     *@bodyParam id int required An id of home team or away team. Example: 1
     *
     * @return \Illuminate\Http\Response
     */
    public function getFinishedMatch(Request $request)
    {
        $user = JWTAuth::user();
        $consumer = Consumer::where('user_id', $user->id)->first();
        $clubId = $consumer->club_id;
        $match = Match::where('status', 'finished')
        ->where(function ($query) use ($clubId) {
            $query->where('home_team_id', $clubId)
            ->orWhere('away_team_id', $clubId);
        })
        ->orderBy('kickoff_time', 'desc')
        ->get();

        return MatchResource::collection($match);
    }
}
