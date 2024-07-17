<?php

namespace App\Repositories;

use App\Models\MatchPlayer;
use App\Models\MatchPlayerVoting;
use App\Models\Player;
use DB;
use Illuminate\Support\Arr;

/**
 * Repository class for model.
 */
class MatchPlayerRepository extends BaseRepository
{
    /**
     * Handle logic to create a new match player.
     *
     * @param $data
     *
     * @return mixed
     */
    public function create($data)
    {
        $matchPlayer = MatchPlayer::create([
            'player_id'     => Arr::get($data, 'player_id', null),
            'match_id'      => $data['match_id'],
            'club_id'       => Arr::get($data, 'club_id', null),
            'type'          => $data['type'],
            'position'      => Arr::get($data, 'position', null),
            'shirt_number'  => Arr::get($data, 'shirt_number', null),
            'is_substitute' => $data['is_substitute'],
        ]);

        return $matchPlayer;
    }

    /**
     * Update eventMatch detail.
     *
     * @param $match
     * @param $data
     *
     * @return mixed
     */
    public function updateMatchPlayerDetail($match, $data)
    {
        $matchPlayer = [];
        $matchPlayerData = MatchPlayer::where('match_id', $match->id)->pluck('id', 'id')->toArray();

        if (!empty($data['line_ups_home_number'])) {
            foreach ($data['line_ups_home_number'] as $key => $value) {
                if ($data['line_ups_home_number_edit'][$key] == '1') {
                    $matchPlayer = MatchPlayer::where('id', $key)->first();
                    $matchPlayer->player_id = $data['line_ups_home_name'][$key];
                    $matchPlayer->shirt_number = $value;
                    $matchPlayer->is_substitute = Arr::get($data, "sub_home.$key", 0);
                    $matchPlayer->save();

                    unset($matchPlayerData[$key]);
                }
            }
        }

        if (!empty($data['line_ups_home_number_new'])) {
            foreach ($data['line_ups_home_number_new'] as $key => $value) {
                if (!empty($data['line_ups_home_number_new'])) {
                    $matchPlayer = MatchPlayer::create([
                        'player_id'     => $data['line_ups_home_name_new'][$key],
                        'match_id'      => $match->id,
                        'club_id'       => $match->home_team_id,
                        'type'          => !empty($data['sub_home_new'][$key]) == 1 ? 'bench' : 'lineup',
                        'shirt_number'  => $value,
                        'is_substitute' => !empty($data['sub_home_new'][$key]),
                    ]);
                }
            }
        }

        if (!empty($data['line_ups_away_number'])) {
            foreach ($data['line_ups_away_number'] as $key => $value) {
                if ($data['line_ups_away_number_edit'][$key] == '1' && isset($data['line_ups_away_name'][$key])) {
                    $matchPlayer = MatchPlayer::where('id', $key)->first();
                    $matchPlayer->player_id = $data['line_ups_away_name'][$key];
                    $matchPlayer->shirt_number = $value;
                    $matchPlayer->type = Arr::get($data, "sub_away.$key", 0) ? 'bench' : 'lineup';
                    $matchPlayer->is_substitute = Arr::get($data, "sub_away.$key", 0);
                    $matchPlayer->save();

                    unset($matchPlayerData[$key]);
                }
            }
        }

        if (!empty($data['line_ups_away_number_new'])) {
            foreach ($data['line_ups_away_number_new'] as $key => $value) {
                if (!empty($data['line_ups_away_number_new'])) {
                    $matchPlayer = MatchPlayer::create([
                        'player_id'     => $data['line_ups_away_name_new'][$key],
                        'match_id'      => $match->id,
                        'club_id'       => $match->away_team_id,
                        'type'          => Arr::get($data, "sub_away_new.$key", 0) ? 'bench' : 'lineup',
                        'shirt_number'  => $value,
                        'is_substitute' => !empty($data['sub_away_new'][$key]),
                    ]);
                }
            }
        }

        foreach ($matchPlayerData as $key => $value) {
            $matchPlayerData = MatchPlayer::where('id', $value)->delete();
        }

        return !empty($matchPlayer) ? $matchPlayer : true;
    }

    /**
     * Handle logic to delete an existing match players.
     *
     * @param $matchId
     *
     * @return mixed
     */
    public function deleteExistingMatchPlayers($matchId)
    {
        $matchPlayer = MatchPlayer::where('match_id', $matchId)->delete();

        return $matchPlayer;
    }

    /**
     * Handle logic to update an existing match players.
     *
     * @param $playerId
     * @param $data
     *
     * @return mixed
     */
    public function update($data, $matchPlayerConditions)
    {
        $matchPlayer = MatchPlayer::where('player_id', $matchPlayerConditions['player_id'])->where('match_id', $matchPlayerConditions['match_id'])->where('club_id', $matchPlayerConditions['club_id'])->first();
        $matchPlayer->position = $data['position'];
        $matchPlayer->shirt_number = Arr::get($data, 'shirt_number', null);
        $matchPlayer->save();

        return $matchPlayer;
    }

    /**
     * Handle logic to delete an existing match players.
     *
     * @param $playerId
     *
     * @return mixed
     */
    public function delete($playerId)
    {
        return MatchPlayer::where('player_id', $playerId)->delete();
    }

    /**
     * Handle logic to delete all existing match players in array.
     *
     * @param $playerIds
     *
     * @return mixed
     */
    public function deleteAll($playerIds)
    {
        return MatchPlayer::whereIn('player_id', $playerIds)->delete();
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
        $vote = MatchPlayerVoting::create([
            'match_id'    => $data['match_id'],
            'player_id'   => $data['player_id'],
            'consumer_id' => $data['consumer_id'],
        ]);

        return $vote;
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
        $matchPlayerVoting = MatchPlayerVoting::with('player')->where('match_id', $matchId)
                                            ->groupBy('player_id')
                                            ->select('player_id', DB::raw('count(*) as voting_count'))
                                            ->orderBy('voting_count', 'desc')
                                            ->first();

        return $matchPlayerVoting;
    }

    /**
     * Get consumer voting for the match.
     *
     * @param $matchId
     *
     * @return mixed
     */
    public function getConsumerVotingForMatch($consumerId, $matchId)
    {
        return MatchPlayerVoting::where('consumer_id', $consumerId)->where('match_id', $matchId)->first();
    }
}
