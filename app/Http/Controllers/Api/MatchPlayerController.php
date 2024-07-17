<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Match\GetMatchPlayersRequest;
use App\Http\Requests\Api\Match\VoteMatchPlayerRequest;
use App\Models\Consumer;
use App\Models\Match;
use App\Models\MatchPlayerVoting;
use App\Services\MatchPlayerService;
use JWTAuth;

/**
 * @group Match player
 *
 * APIs for Match player.
 */
class MatchPlayerController extends BaseController
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
    public function __construct(MatchPlayerService $service)
    {
        $this->service = $service;
    }

    /**
     * Get match players
     * Get match players list with no of votes.
     *
     * @bodyParam match_id int required An id of a match. Example: 1
     *
     * @return \Illuminate\Http\Response
     */
    public function getMatchPlayersWithVotes(GetMatchPlayersRequest $request)
    {
        $user = JWTAuth::user();
        $consumer = Consumer::where('user_id', $user->id)->first();
        $matchPlayerVoting = MatchPlayerVoting::where('consumer_id', $consumer->id)->where('match_id', $request['match_id'])->first();
        $matchPlayersWithVotes = $this->service->getMatchPlayersWithVotes($request['match_id']);
        $matchStatus = Match::find($request['match_id'])->status;

        return response()->json([
            'match_players_with_votes' 	 => collect($matchPlayersWithVotes)->sortByDesc('votes')->values()->all(),
            'is_match_finished'          => $matchStatus == 'finished' ? true : false,
            'already_voted_player'       => $matchPlayerVoting ? $matchPlayerVoting : null,
        ]);
    }

    /**
     * Vote match player
     * Vote match player and get match players with updated data.
     *
     * @bodyParam match_id int required An id of a match. Example: 1
     * @bodyParam player_id int required An id of a player. Example: 1
     *
     * @return \Illuminate\Http\Response
     */
    public function voteMatchPlayer(VoteMatchPlayerRequest $request)
    {
        $user = JWTAuth::user();

        return $this->service->voteAndGetMatchPlayers($user, $request->all());
    }
}
