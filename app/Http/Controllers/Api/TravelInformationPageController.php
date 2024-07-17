<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\TravelInformationPage\TravelInformationPage as TravelInformationPageResource;
use App\Http\Resources\TravelOffer\TravelOffer as TravelOfferResource;
use App\Http\Resources\TravelWarning\TravelWarning as TravelWarningResource;
use App\Models\Consumer;
use App\Models\TravelInformationPage;
use App\Models\TravelOffer;
use App\Services\TravelInformationPageService;
use App\Services\TravelWarningService;
use JWTAuth;

/**
 * @group Travel Information Pages
 *
 * APIs for Travel Information Pages.
 */
class TravelInformationPageController extends BaseController
{
    /**
     * Create a service variable.
     *
     * @return void
     */
    protected $service;
    protected $travelWarningService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TravelInformationPageService $service, TravelWarningService $travelWarningService)
    {
        $this->service = $service;
        $this->travelWarningService = $travelWarningService;
    }

    /**
     * Get travel information pages
     * Get all published travel information pages.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTravelInformationPages()
    {
        $user = JWTAuth::user();
        $consumer = Consumer::where('user_id', $user->id)->first();
        $travelInformationPages = TravelInformationPage::where('status', 'Published')->where('club_id', $consumer->club_id)->where('publication_date', '<', now())->get();

        $travelWarnings = $this->travelWarningService->getTravelWarnings($user->id);

        return response()->json([
            'travel_information_pages' => TravelInformationPageResource::collection($travelInformationPages),
            'travel_warnings' => TravelWarningResource::collection($travelWarnings)
        ]);
    }

    /**
     * Get travel offers and information pages
     * Get all published travel offers and information pages.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTravelOfferAndInformation()
    {
        $travelOfferAndInformationData = [];
        $user = JWTAuth::user();
        $consumer = Consumer::where('user_id', $user->id)->first();
        $travelInformationPages = TravelInformationPage::where('status', 'Published')->where('club_id', $consumer->club_id)->where('publication_date', '<', now())->get();
        $travelOffers = TravelOffer::where('status', 'Published')->where('club_id', $consumer->club_id)->where('publication_date', '<=', now())->where('show_until', '>=', now())->get();
        $travelOfferAndInformationData['travel_information_pages'] = TravelInformationPageResource::collection($travelInformationPages)->sortByDesc('publication_date')->values();
        $travelOfferAndInformationData['travel_offers'] = TravelOfferResource::collection($travelOffers)->sortByDesc('publication_date')->values();
        $travelOfferAndInformationData['find_your_seat'] = ['title' => 'Find your seat', 'icon'=>''];
        $travelOfferAndInformationData['direction_to_stadium'] = ['title'=> 'Direction to stadium', 'icon'=>''];

        return $travelOfferAndInformationData;
    }
}
