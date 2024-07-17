<?php

namespace App\Repositories;

use App\Models\Scorer;

/**
 * Repository class for scorer model.
 */
class ScorerRepository extends BaseRepository
{
    /**
     * Handle logic to create new scorer.
     *
     * @param $data
     *
     * @return mixed
     */
    public function create($data)
    {
        $scorer = Scorer::create([
            'competition_id'   => $data['competition_id'],
            'season_id'        => $data['season_id'],
            'player_id'        => $data['player_id'],
            'first_name'       => $data['first_name'],
            'last_name'        => $data['last_name'],
            'date_of_birth'    => $data['date_of_birth'],
            'country_of_birth' => $data['country_of_birth'],
            'nationality'      => $data['nationality'],
            'position'         => $data['position'],
            'number_of_goals'  => $data['number_of_goals'],
        ]);

        return $scorer;
    }

    /**
     * Delete existing scorers.
     *
     * @param $competitionId
     *
     * @return mixed
     */
    public function deleteExistingScorers($competitionId)
    {
        $standing = Scorer::where('competition_id', $competitionId)->delete();

        return $standing;
    }
}
