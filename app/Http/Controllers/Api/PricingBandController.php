<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\PricingBand\GetPricingBandRequest;
use App\Models\Consumer;
use App\Models\PricingBand;
use App\Models\StadiumGeneralSetting;
use Illuminate\Http\Request;
use App\Services\PricingBandService;
use JWTAuth;

/**
 * @group Pricing bands
 *
 * APIs for Pricing bands.
 */
class PricingBandController extends BaseController
{
	/**
     * Create a pricing band service variable.
     *
     * @return void
     */
    protected $service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PricingBandService $pricingBandService)
    {
    	$this->service = $pricingBandService;
    }

    /**
     * Destory/Unset object variables.
     *
     * @return void
     */
    public function __destruct()
    {
    }

    /**
     * Get pricing bands
     * Get pricing bands.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPricingBands(GetPricingBandRequest $request)
    {
        $user = JWTAuth::user();
        $consumer = Consumer::where('user_id', $user->id)->first();
        $pricingBands = $this->service->getPricingBands($consumer, $request->all());
        return $pricingBands;
    }
}
