<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ClubInformationPage\ClubInformationPage as ClubInformationPageResource;
use App\Models\Consumer;
use App\Models\ClubInformationPage;
use App\Services\ClubInformationPageService;
use JWTAuth;

/**
 * @group My Club
 *
 * APIs for Club Information Pages.
 */
class ClubInformationPageController extends BaseController
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
    public function __construct(ClubInformationPageService $service)
    {
        $this->service = $service;
    }

    /**
     * Get club information pages
     * Get all published club information pages.
     *
     * @return \Illuminate\Http\Response
     */
    public function getClubInformationPages()
    {
        $user = JWTAuth::user();
        $consumer = Consumer::where('user_id', $user->id)->first();
        $clubInformationPages = ClubInformationPage::where('status', 'Published')->where('club_id', $consumer->club_id)->where('publication_date', '<', now())->get();

        return ClubInformationPageResource::collection($clubInformationPages);
    }
}
