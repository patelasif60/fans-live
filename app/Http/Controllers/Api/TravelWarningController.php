<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\TravelWarning\TravelWarning as TravelWarningResource;
use App\Models\Consumer;
use App\Models\TravelWarning;
use App\Services\TravelWarningService;
use JWTAuth;

/**
 * @group Travel Warnings
 *
 * APIs for Travel Warnings.
 */
class TravelWarningController extends BaseController
{
    /**
     * Create a service variable.
     *
     * @return void
     */
    protected $service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TravelWarningService $service)
    {
        $this->service = $service;
    }

    /**
     * Get travel warnings
     * Get all published travel warnings.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTravelWarnings()
    {
        $user = JWTAuth::user();
        $travelWarnings = $this->service->getTravelWarnings($user->id);

        return TravelWarningResource::collection($travelWarnings);
    }
}
