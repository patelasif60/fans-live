<?php

namespace App\Repositories;

use App\Models\StadiumBlock;
use App\Models\StadiumBlockSeat;
use DB;

/**
 * Repository class for stadium block model.
 */
class StadiumBlockRepository extends BaseRepository
{
    /**
     * Handle logic to create a new stadium block.
     *
     * @param $clubId
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($clubId, $user, $data)
    {
        $stadiumBlock = StadiumBlock::create([
            'club_id'                => $clubId,
            'name'                   => $data['name'],
            'seating_plan'           => $data['seating_plan'],
            'seating_plan_file_name' => $data['seating_plan_file_name'],
            'area'                   => isset($data['pos_data']) ? $data['pos_data'] : null,
            'is_active'              => isset($data['is_active']) ? $data['is_active'] : 0,
            'created_by'             => $user->id,
            'updated_by'             => $user->id,
        ]);

        return $stadiumBlock;
    }

    /**
     * Handle logic to update a stadium block.
     *
     * @param $user
     * @param $stadiumBlock
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $stadiumBlock, $data)
    {
        $stadiumBlock->fill([
            'name'                   => $data['name'],
            'seating_plan'           => $data['seating_plan'],
            'seating_plan_file_name' => $data['seating_plan_file_name'],
            'area'                   => isset($data['pos_data']) ? $data['pos_data'] : null,
            'is_active'              => isset($data['is_active']) ? $data['is_active'] : 0,
            'created_by'             => $user->id,
            'updated_by'             => $user->id,
        ]);
        $stadiumBlock->save();

        return $stadiumBlock;
    }

    /**
     * Get stadium blocks user data.
     *
     * @param $data
     *
     * @return mixed
     */
    public function getData($clubId, $data)
    {
        $stadiumBlockData = DB::table('stadium_blocks')->where('club_id', $clubId);

        if (isset($data['sortby'])) {
            $sortby = $data['sortby'];
            $sorttype = $data['sorttype'];
        } else {
            $sortby = 'stadium_blocks.id';
            $sorttype = 'desc';
        }
        $stadiumBlockData = $stadiumBlockData->orderBy($sortby, $sorttype);

        $pricingBandListData = [];

        if (!array_key_exists('pagination', $data)) {
            $stadiumBlockData = $stadiumBlockData->paginate($data['pagination_length']);
            $pricingBandListData = $stadiumBlockData;
        } else {
            $pricingBandListData['total'] = $stadiumBlockData->count();
            $pricingBandListData['data'] = $stadiumBlockData->get();
        }

        $response = $pricingBandListData;

        return $response;
    }

    /**
	 * Handle logic to get stadium block.
	 *
	 * @param $id
	 *
	 * @return mixed
	 */
	public function stadiumBlock($id)
	{
		return StadiumBlock::find($id);
	}

    /**
     * Handle logic to get stadium block seats.
     *
     * @param $seat
     * @param $blockId
     *
     * @return mixed
     */
    public function getStadiumBlockSeats($seat, $blockId)
    {
        return StadiumBlockSeat::where(\DB::raw("CONCAT(`row`,`seat`)"), "=", $seat)->where('stadium_block_id', $blockId)->first();
    }

    /**
     * Handle logic to get blocks list.
     *
     * @param $clubId
     *
     * @return mixed
     */
    public function getBlocks($clubId)
    {
        $stadiumBlocks = StadiumBlock::where('club_id', $clubId)->get()->pluck('name', 'id');

        return $stadiumBlocks;
    }

    /**
     * get stadium block name
     *
     *
     * @return mixed
     */
    public function dbBlockNameArray()
    {
        return StadiumBlock::select('name')->get()->toArray();
    }

    /**
     * Get active stedium block.
     *
     * @param
     *
     * @return mixed
     */

    public function getActiveStadiumBlock($clubId)
    {
        return StadiumBlock::where('club_id', $clubId)->where('is_active', 1)->get();
    }
}
