<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\StadiumGeneralSetting\GetDirectionsToStadiumRequest;
use App\Http\Requests\Api\StadiumGeneralSetting\FindMySeatToStadiumRequest;
use App\Http\Resources\StadiumGeneralSetting\StadiumGeneralSetting as StadiumGeneralSettingResource;
use App\Http\Resources\StadiumBlock\StadiumBlock as StadiumBlockResource;
use App\Models\StadiumBlock;
use App\Models\StadiumBlockSeat;
use App\Models\StadiumGeneralSetting;
use DB;

/**
 * @group Stadium
 *
 * APIs for Stadium.
 */
class StadiumGeneralSettingController extends BaseController
{
	/**
	 * Get directions to stadium
	 * Get directions to stadium by club.
	 *
	 * @bodyParam club_id int required An id of a club. Example: 1
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getDirectionsToStadium(GetDirectionsToStadiumRequest $request)
	{
		$directions = StadiumGeneralSetting::where('club_id', $request['club_id'])->get();

		return StadiumGeneralSettingResource::collection($directions);
	}

	/**
	 * Find my Seat
	 * Find my seat by seat number
	 *
	 * @bodyParam block_id int required An id of a block.
	 * @bodyParam seat int required, number of a Seat. Example : A15 (combination of row and seat)
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function findMySeatToStadium(FindMySeatToStadiumRequest $request)
	{
		$stadiumBlockSeat = StadiumBlockSeat::where(DB::raw("CONCAT(`row`,`seat`)"), "=", $request['seat'])->where('stadium_block_id', $request['block_id'])->get();
		if ((count($stadiumBlockSeat)) == 0) {
			return response()->json([
                'message' => 'No such seat found.'
            ], 404);
		}
		$stadiumBlock = StadiumBlock::find($request['block_id']);
		return new StadiumBlockResource($stadiumBlock);
	}
}
