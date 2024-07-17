<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\PaymentIQ\IntegrationService;


/**
 * @group Payment IQ
 *
 * APIs for Payment IQ
 */
class PaymentIQController extends BaseController
{
	/**
     * Create a paymentiq service variable.
     *
     * @return void
     */
    protected $service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(IntegrationService $service)
    {
    	$this->service = $service;
    }

    /**
     * Verify user
     * Verify user.
     *
     * @bodyParam sessionId string required The sessionId. Example: Banana
     * @bodyParam userId string required The id of the user. Example: 123456
     */
    public function verifyUser(Request $request)
    {
    	$userDetail = $this->service->verifyUser($request);
    	return response()->json($userDetail);
    }

    /**
     * Authorize
     * Authorize.
     *
     * @bodyParam sessionId string required The sessionId. Example: Banana
     * @bodyParam userId string required The id of the user. Example: 123456
     */
    public function authorize(Request $request)
    {
    	\Log::info("authorize");
    	\Log::info($request->all());
    	$authorizationDetail = $this->service->authorize($request->all());
    	return response()->json($authorizationDetail);
    }

    /**
     * Transfer
     * Transfer.
     *
     * @bodyParam sessionId string required The sessionId. Example: Banana
     * @bodyParam userId string required The id of the user. Example: 123456
     */
    public function transfer(Request $request)
    {
    	\Log::info($request->all());
    	$transferDetail = $this->service->transfer($request->all());
    	\Log::info($transferDetail);
    	return response()->json($transferDetail);
    }

    /**
     * Verify user
     * Verify user.
     *
     * @bodyParam sessionId string required The sessionId. Example: Banana
     * @bodyParam userId string required The id of the user. Example: 123456
     */
    public function cancel(Request $request)
    {
        $transferDetail = $this->service->cancel($request->all());
    }
}
