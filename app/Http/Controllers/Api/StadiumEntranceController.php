<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\StadiumEntrance\GetDirectionsToStadiumRequest;
use App\Http\Resources\StadiumEntrance\StadiumEntrance as StadiumEntranceResource;
use App\Models\StadiumEntrance;
use App\Services\StadiumEntranceService;

/**
 * @group Stadium
 *
 * APIs for Stadium.
 */
class StadiumEntranceController extends BaseController
{
    /**
     * Create a stadium entrance service variable.
     *
     * @return void
     */
    protected $service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(StadiumEntranceService $service)
    {
        $this->service = $service;
    }

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
        $directions = StadiumEntrance::where('club_id', $request['club_id'])->get();

        return StadiumEntranceResource::collection($directions);
    }
}
