<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Standing\GetStandingRequest;
use App\Http\Resources\Standing\Standing as StandingResource;
use App\Models\Standing;
use App\Services\StandingService;

/**
 * @group Standing
 *
 * APIs for Standing.
 */
class StandingController extends BaseController
{
    /**
     * Create a standing service variable.
     *
     * @return void
     */
    protected $service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(StandingService $service)
    {
        $this->service = $service;
    }

    /**
     * Get Standing
     * Get all standings.
     *
     * @bodyParam competition_id int required An id of a competition. Example: 1
     *
     * @return \Illuminate\Http\Response
     */
    public function getStandings(GetStandingRequest $request)
    {
        $standings = Standing::where('competition_id', $request['competition_id'])->get();

        return StandingResource::collection($standings);
    }
}
