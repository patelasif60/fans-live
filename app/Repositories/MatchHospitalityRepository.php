<?php

namespace App\Repositories;

use App\Models\MatchHospitality;
use App\Models\MatchHospitalityHospitalitySuite;
use Illuminate\Support\Arr;

/**
 * Repository class for model.
 */
class MatchHospitalityRepository extends BaseRepository
{
    /**
     * Handle logic to create a new match event.
     *
     * @param $data
     *
     * @return mixed
     */
    public function create($data)
    {
        return MatchHospitality::create([
            'match_id'                    => $data['match_id'],
            'rewards_percentage_override' => Arr::get($data, 'rewards_percentage_override', null),
        ]);
    }

    /**
     * Handle logic to update a new match hospitality.
     *
     * @param $matchHospitality
     * @param $data
     *
     * @return mixed
     */
    public function update($matchHospitality, $data)
    {
        $dbFields = [
            'rewards_percentage_override' => Arr::get($data, 'rewards_percentage_override', null),
        ];
        if ($matchHospitality) {
            $matchHospitality = $matchHospitality->fill($dbFields);
        } else {
            $dbFields['match_id'] = $data['match_id'];
            $matchHospitality = MatchHospitality::create($dbFields);
        }
        $matchHospitality->save();

        return $matchHospitality;
    }

    /**
     * Handle logic to delete an existing match event.
     *
     * @param $id
     *
     * @return mixed
     */
    public function delete($id)
    {
        return MatchHospitality::where('id', $id)->delete();
    }

    public function getDataById($id)
    {
        return MatchHospitality::find($id);
    }

    public function getDataWithCondition($where = [])
    {
        return MatchHospitality::where($where);
    }

    /**
     * Handle logic to create a new match hospitality hospitality suite.
     *
     * @param $matchTicketingId
     * @param $blockId
     *
     * @return mixed
     */
    public function createHospitalitySuite($matchHospitalityId, $suiteId)
    {
        $matchHospitalityHospitalitySuite = MatchHospitalityHospitalitySuite::create([
            'match_hospitality_id' => $matchHospitalityId,
            'hospitality_suite_id' => $suiteId,
        ]);

        return $matchHospitalityHospitalitySuite;
    }

    /**
     * Handle logic to destroy a existing match hospitality hospitality suite.
     *
     * @param $matchHospitalityId
     *
     */
    public function deleteSuite($matchHospitalityId)
    {
        $matchHospitalityHospitalitySuite = MatchHospitalityHospitalitySuite::where('match_hospitality_id', $matchHospitalityId)->delete();
    }
}
