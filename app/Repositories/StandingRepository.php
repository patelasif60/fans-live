<?php

namespace App\Repositories;

use App\Models\Match;
use App\Models\Standing;

/**
 * Repository class for Standing model.
 */
class StandingRepository extends BaseRepository
{
    /**
     * Handle logic to create new standing.
     *
     * @param $data
     *
     * @return mixed
     */
    public function create($data)
    {
        $standing = Standing::create([
            'competition_id'  => $data['competition_id'],
            'stage'           => $data['stage'],
            'type'            => $data['type'],
            'group'           => $data['group'],
            'position'        => $data['position'],
            'club_id'         => $data['club_id'],
            'played_games'    => $data['played_games'],
            'won'             => $data['won'],
            'draw'            => $data['draw'],
            'lost'            => $data['lost'],
            'points'          => $data['points'],
            'goal_for'        => $data['goal_for'],
            'goal_against'    => $data['goal_against'],
            'goal_difference' => $data['goal_difference'],
        ]);

        return $standing;
    }

    /**
     * Get matches with finished status.
     *
     * @return mixed
     */
    public function getFinishedMatches()
    {
        $matches = Match::where('status', 'FINISHED')->get();

        return $matches;
    }

    /**
     * Delete existing standings.
     *
     * @param $competitionId
     *
     * @return mixed
     */
    public function deleteExistingStandings($competitionId)
    {
        $standing = Standing::where('competition_id', $competitionId)->delete();

        return $standing;
    }
}
