<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CTA\GetCTADetailsRequest;
use App\Http\Requests\Api\CTA\GetCTAsRequest;
use App\Http\Resources\CTA\CTA as CTAResource;
use App\Models\CTA;
use App\Services\CTAService;

/**
 * @group CTAs
 *
 * APIs for CTAs.
 */
class CTAController extends BaseController
{
    /**
     * Create a cta service variable.
     *
     * @return void
     */
    protected $service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CTAService $service)
    {
        $this->service = $service;
    }

    /**
     * Get CTAs
     * Get all published CTAs of a club.
     *
     * @bodyParam club_id int required An id of a club. Example: 1
     *
     * @return \Illuminate\Http\Response
     */
    public function getCtas(GetCTAsRequest $request)
    {
        $ctas = CTA::where('club_id', $request['club_id'])->where('status', 'Published')->where('publication_date', '<', now())->get();

        return CTAResource::collection($ctas);
    }

    /**
     * Get CTA details
     * Get CTA details.
     *
     * @bodyParam id int required An id of a CTA. Example: 1
     *
     * @return \Illuminate\Http\Response
     */
    public function getCTADetails(GetCTADetailsRequest $request)
    {
        $cta = CTA::where('id', $request['id'])->first();

        return new CTAResource($cta);
    }
}
