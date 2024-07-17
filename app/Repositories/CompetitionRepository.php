<?php

namespace App\Repositories;

use App\Models\Competition;
use DB;

/**
 * Repository class for User model.
 */
class CompetitionRepository extends BaseRepository
{
    /**
     * Handle logic to create a new cms user.
     *
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($user, $data)
    {
        $competition = Competition::create([
            'name'            => $data['name'],
            'external_app_id' => $data['external_app_id'],
            'logo'            => $data['logo'],
            'logo_file_name'  => $data['logo_file_name'],
            'is_primary'      => isset($data['is_primary']) ? $data['is_primary'] : 0,
            'status'          => $data['status'],
            'created_by'      => $user->id,
            'updated_by'      => $user->id,
        ]);

        $this->attachClubs($competition, json_decode($data['competition_clubs']));

        return $competition;
    }

    /**
     * Handle logic to update a category.
     *
     * @param $user
     * @param $competition
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $competition, $data)
    {
        $competition->fill([
            'name'            => $data['name'],
            'external_app_id' => $data['external_app_id'],
            'logo'            => $data['logo'],
            'logo_file_name'  => $data['logo_file_name'],
            'is_primary'      => isset($data['is_primary']) ? $data['is_primary'] : 0,
            'status'          => $data['status'],
            'created_by'      => $user->id,
            'updated_by'      => $user->id,
        ]);
        $competition->save();

        $this->attachClubs($competition, json_decode($data['competition_clubs']));

        return $competition;
    }

    /**
     * Get Competitoin data.
     *
     * @param $data
     *
     * @return mixed
     */
    public function getData($data)
    {
        $competitionData = DB::table('competitions');

        if (isset($data['sortby'])) {
            $sortby = $data['sortby'];
            $sorttype = $data['sorttype'];
        } else {
            $sortby = 'competitions.id';
            $sorttype = 'desc';
        }
        $competitionData = $competitionData->orderBy($sortby, $sorttype);

        if (isset($data['name']) && trim($data['name']) != '') {
            $competitionData->where('competitions.name', 'like', '%'.$data['name'].'%');
        }

        $competitionListArray = [];

        if (!array_key_exists('pagination', $data)) {
            $competitionData = $competitionData->paginate($data['pagination_length']);
            $competitionListArray = $competitionData;
        } else {
            $competitionListArray['total'] = $competitionData->count();
            $competitionListArray['data'] = $competitionData->get();
        }

        $response = $competitionListArray;

        return $response;
    }

    /**
     * Attach clubs to competition.
     *
     * @param $competition
     * @param $clubs
     *
     * @return mixed
     */
    public function attachClubs($competition, $clubs)
    {
        $competition->clubs()->sync($clubs);
    }

    /**
     * Get competitions with App Id.
     *
     * @return mixed
     */
    public function getCompetitionsWithAppId()
    {
        $competitions = Competition::whereNotNull('external_app_id')->get();

        return $competitions;
    }

	/**
	 * Handle logic to get Competition Count.
	 *
	 *
	 * @return mixed
	 */
	public function getCompetitionCount()
	{
		$competitionCount = Competition::count();
		return $competitionCount;
	}

}
