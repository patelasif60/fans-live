<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\TravelOffer\TravelOffer as TravelOfferResource;
use App\Models\Consumer;
use App\Models\TravelOffer;
use App\Services\TravelOfferService;
use JWTAuth;

/**
 * @group Travel Offers
 *
 * APIs for Travel Offers.
 */
class TravelOfferController extends BaseController
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
    public function __construct(TravelOfferService $service)
    {
        $this->service = $service;
    }

    /**
     * Get travel special offers
     * Get all published travel special offers.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTravelSpecialOffers()
    {
        $user = JWTAuth::user();
        $consumer = Consumer::where('user_id', $user->id)->first();
        $travelOffers = TravelOffer::where('status', 'Published')->where('club_id', $consumer->club_id)->where('publication_date', '<=', now())->where('show_until', '>=', now())->get();

        return TravelOfferResource::collection($travelOffers);
    }
}
