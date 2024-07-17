<?php

namespace App\Repositories;

use App\Models\StadiumBlock;
use App\Models\StadiumBlockStadiumEntrance;
use App\Models\StadiumEntrance;

/**
 * Repository class for User model.
 */
class StadiumEntranceRepository extends BaseRepository
{
    /**
     * Handle logic to update a category.
     *
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $clubId, $data)
    {
        $blocksMapping = [];

        $entranceData = array_filter(json_decode($data['dbdata']));
        //dd($entranceData);
        $stadiumEntrance = StadiumEntrance::where('club_id', $clubId)->whereNotIn('id', array_filter(array_column($entranceData, 'id')))->delete();
        foreach ($entranceData as $key => $val) {
            $dbFields = [
                'name'       => $val->name,
                'latitude'   => $val->latitude,
                'longitude'  => $val->longitude,
                'club_id'    => $clubId,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ];
            if ($val->id > 0) {
                $stadiumEntrance = StadiumEntrance::where('id', $val->id)->update($dbFields);
            } else {
                $stadiumEntrance = StadiumEntrance::create($dbFields);
                $stadiumEntrance->save();
            }

            if (!empty($val->blocks)) {
                $stadiumEntrance = StadiumEntrance::where([['club_id', $clubId], ['name', $val->name]])->first();

                $getBlockResult = StadiumBlock::whereIn('name', array_map('trim', explode(',', $val->blocks)))->where('club_id', $clubId)->get()->pluck('id');

                $blocksMapping[$stadiumEntrance->id] = $getBlockResult;
            }
        }

        if (count($blocksMapping) > 0) {
            foreach ($blocksMapping as $key=>$val) {
                $BlocksMappingDelete = StadiumBlockStadiumEntrance::where('stadium_entrance_id', $key)->delete();

                foreach ($val as $key1=> $val1) {
                    $dbFields = [
                        'stadium_block_id'   => $val1,
                        'stadium_entrance_id'=> $key,
                    ];
                    StadiumBlockStadiumEntrance::insert($dbFields);
                }
            }
        }

        return $stadiumEntrance;
    }

    /**
     * Handle logic to get stadium entrances.
     *
     * @param $$stadiumEntranceIds
     *
     * @return mixed
     */
    public function getstadiumEntrances($stadiumEntranceIds)
    {
        $stadiumEntrances = StadiumEntrance::whereIn('id', $stadiumEntranceIds)->pluck('name')->toArray();

        return implode(', ', $stadiumEntrances);
    }
}
