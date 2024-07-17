<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\Consumer;
use App\Models\Match;
use App\Models\PricingBand;
use App\Models\PricingBandSeat;
use App\Models\StadiumBlockSeat;
use App\Models\StadiumBlock;
use App\Models\StadiumGeneralSetting;
use App\Services\ConsumerService;
use App\Services\StadiumBlockService;
use Illuminate\Http\Request;
use JavaScript;

class StadiumBlockController extends Controller
{
    /**
     * A consumer user service.
     *
     * @var service
     */
    protected $consumerService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ConsumerService $consumerService, StadiumBlockService $stadiumBlockService)
    {
        $this->consumerService = $consumerService;
        $this->stadiumBlockService = $stadiumBlockService;
    }

    /**
     * Pick stadium block.
     */
    public function pickBlock(Request $request, $clubId, $matchId)
    {
        $club = Club::find($clubId);
        $stadiumGeneralSetting = $club->stadium;
        $matchPricingBands = Match::find($matchId)->ticketing->pricingBrand()->pluck('pricing_band_id')->toArray();
        $priceBandSeats = $this->stadiumBlockService->getPriceBandSeat($clubId, $matchPricingBands);
        $priceRanges = $this->stadiumBlockService->getPriceRange($clubId, $matchPricingBands);
        $availableSeat = $this->stadiumBlockService->availableSeat($matchId, $clubId);
        $priceBandSeatArray = $this->stadiumBlockService->getPriceBandSeatArray($priceBandSeats, $priceRanges);
        $priceRangeArray = $priceRanges->toArray();
        $area = $this->stadiumBlockService->getArea($clubId);
        $clubPrimaryColor = $club->primary_colour;
        $clubSecondaryColor = $club->secondary_colour;
        JavaScript::put([
            'matchId' => $matchId,
        ]);
        return view('frontend.matchticketbooking.pickblock', compact('area', 'stadiumGeneralSetting', 'priceBandSeatArray','priceRangeArray','availableSeat', 'clubPrimaryColor', 'clubSecondaryColor'));
    }

    /**
     * Pick stadium seat.
     */
    public function getBlockSeat(Request $request)
    {
        $requestData = $request->all();
        $bookedTickets = $this->stadiumBlockService->bookedTicket($request['matchId']);
        $matchPricingBands = Match::find($request['matchId'])->ticketing->pricingBrand()->pluck('pricing_band_id')->toArray();
        $row = StadiumBlockSeat::where('stadium_block_id', $request['blockId'])->distinct()->get(['row']);
        $stadiumBlockSeat = StadiumBlockSeat::where('stadium_block_id', $request['blockId'])->get();
        $seatArray = $this->stadiumBlockService->getSeatArray($stadiumBlockSeat, $requestData, $bookedTickets);
        $priceBandSeat = PricingBandSeat::select('pricing_band_seats.*', 'price', 'vat_rate', 'display_name', 'pricing_bands.id as pricing_band_id')
        			->join('pricing_bands', 'pricing_bands.id', '=', 'pricing_band_seats.pricing_band_id')
        			->where('stadium_block_id', $request['blockId'])
        			->whereIn('pricing_bands.id', $matchPricingBands)
        			->get();
        $priceBandSeatArray = $this->stadiumBlockService->getPriceBandSeatArray($priceBandSeat);
        $seatData = $this->stadiumBlockService->getPriceBlockSeatArray($priceBandSeatArray, $seatArray, $row);
        krsort($seatData);
        return view('frontend.matchticketbooking.pickseat', compact('seatData', 'requestData'));
    }
}
