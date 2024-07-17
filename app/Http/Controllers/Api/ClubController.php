<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Club\GetClubDetailsRequest;
use App\Http\Requests\Api\Club\SetDefaultRequest;
use App\Http\Resources\Club\Club as ClubResource;
use App\Models\Club;
use App\Services\ClubService;

/**
 * @group Club
 *
 * APIs for Club.
 */
class ClubController extends BaseController
{
    /**
     * Create a club service variable.
     *
     * @return void
     */
    protected $service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ClubService $service)
    {
        $this->service = $service;
    }

    /**
     * Set club
     * Pick up the club.
     *
     * @bodyParam club_id integer required The club id of the club.
     */
    public function setDefaultClub(SetDefaultRequest $request)
    {
        $status = $this->service->setDefaultClub($request->all());
        if ($status) {
            $club = Club::where('id', $request['club_id'])->first();
            return response()->json([
                'message' => 'Default club have been set successfully.',
                'club' => new ClubResource($club),
            ]);
        }

        return response()->json([
            'message' => 'No such club found.',
        ]);
    }

    /**
     * Get club details
     * Get club details.
     *
     * @bodyParam id integer required An id of the club. Example: 1
     */
    public function getClubDetails(GetClubDetailsRequest $request)
    {
        $club = Club::where('id', $request['id'])->first();

        return new ClubResource($club);
    }
}
