<?php

namespace App\Services;

use App\Http\Resources\MatchPlayerVoting\MatchPlayerVoting as MatchPlayerVotingResource;
use App\Models\Consumer;
use App\Models\Match;
use App\Models\MatchPlayer;
use App\Models\MatchPlayerVoting;
use App\Repositories\MatchPlayerRepository;

/**
 * User class to handle operator interactions.
 */
class MatchPlayerService
{
    /**
     * Create a new service instance.
     *
     * @param MatchPlayerRepository $matchPlayerRepository
     */
    public function __construct(MatchPlayerRepository $matchPlayerRepository)
    {
        $this->matchPlayerRepository = $matchPlayerRepository;
    }

    /**
     * Destory/Unset object variables.
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->matchPlayerRepository);
    }

    /**
     * Get match player with votes and also get match status.
     *
     * @param $clubId
     * @param $data
     *
     * @return mixed
     */
    public function getMatchPlayersWithVotes($matchId)
    {
        $matchPlayers = MatchPlayer::where('match_id', $matchId)->get();
        $matchPlayerWithVotes = MatchPlayerVotingResource::collection($matchPlayers);

        return $matchPlayerWithVotes;
    }

    /**
     * Handle logic to create vote match player entry.
     *
     * @param $data
     *
     * @return mixed
     */
    public function voteMatchPlayer($data)
    {
        return $this->matchPlayerRepository->voteMatchPlayer($data);
    }

    /**
     * Handle logic to create vote match player and return match players.
     *
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function voteAndGetMatchPlayers($user, $data)
    {
        $matchStatus = Match::find($data['match_id'])->status;
        if ($matchStatus !== 'in_play' && $matchStatus !== 'paused' && $matchStatus !== 'live') {
            return response()->json([
                'message' => 'You can not vote player of this match.',
            ], 403);
        }
        $consumer = Consumer::where('user_id', $user->id)->first();
        $data['consumer_id'] = $consumer->id;
        $matchPlayerVoting = MatchPlayerVoting::where('match_id', $data['match_id'])->where('consumer_id', $consumer->id)->first();
        if ($matchPlayerVoting) {
            $matchPlayerVoting->delete();
        }
        $matchPlayerVotingData = $this->voteMatchPlayer($data);
        $matchPlayersWithVotes = $this->getMatchPlayersWithVotes($data['match_id']);
        if ($matchPlayerVotingData) {
            return response()->json([
                'message'                    => 'Player have been voted successfully.',
                'match_players_with_votes'   => collect($matchPlayersWithVotes)->sortByDesc('votes')->values()->all(),
                'is_match_finished'          => $matchStatus == 'finished' ? true : false,
                'already_voted_player'       => $matchPlayerVotingData ? $matchPlayerVotingData : null,
            ]);
        }

        return response()->json([
            'message' => 'Something went wrong.',
        ], 500);
    }

    /**
     * Get player of the match.
     *
     * @param $matchId
     *
     * @return mixed
     */
    public function getPlayerOfTheMatch($matchId)
    {
        return $this->matchPlayerRepository->getPlayerOfTheMatch($matchId);
    }

    /**
     * Get consumer voting for the match.
     *
     * @param $consumerId
     * @param $matchId
     *
     * @return mixed
     */
    public function getConsumerVotingForMatch($consumerId, $matchId)
    {
        return $this->matchPlayerRepository->getConsumerVotingForMatch($consumerId, $matchId);
    }
}
