<?php

namespace App\Http\Controllers\Api;

use App\Models\ProductTransaction;
use App\Models\CollectionPoint;
use App\Models\ProductAndLoyaltyRewardTransactionCollection;
use App\Services\CollectionPointService;
use App\Http\Resources\CollectionPoint\CollectionPoint as CollectionPointResource;
use App\Services\StaffService;
use App\Services\MatchService;
use App\Services\ClubService;
use Illuminate\Http\Request;
use App\Http\Requests\Api\CollectionPoint\GetCollectionPointsRequest;
use App\Http\Requests\Api\CollectionPoint\GetProductAndLoyaltyRewardTransactionsCollectionPointWise;
use App\Http\Requests\Api\CollectionPoint\ChangeOrderStatus;
use App\Http\Requests\Api\CollectionPoint\ScanOrder;
use App\Http\Resources\ProductTransaction\ProductTransaction as ProductTransactionResource;
use App\Http\Resources\LoyaltyRewardTransaction\LoyaltyRewardTransaction as LoyaltyRewardTransactionResource;
use App\Models\LoyaltyRewardTransaction;
use JWTAuth;
use Carbon\Carbon;

/**
 * @group Collection Points
 *
 * APIs for Collection Points.
 */
class CollectionPointController extends BaseController
{
	/**
	 * Create a collection point service variable.
	 *
	 * @return void
	 */
	protected $service;
	protected $staffService;
	protected $matchService;
	protected $clubService;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(CollectionPointService $service, StaffService $staffService, MatchService $matchService, ClubService $clubService)
	{
		$this->service = $service;
		$this->staffService = $staffService;
		$this->matchService = $matchService;
		$this->clubService = $clubService;
	}

	/**
	 * Get Collection Points
	 * Get all published collection points of a club.
	 *
	 * @bodyParam club_id int required An id of a club. Example: 1
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getCollectionPoints(GetCollectionPointsRequest $request)
	{
		$collectionPoints = CollectionPoint::where('club_id', $request['club_id'])->where('status', 'Published')->get();

		return CollectionPointResource::collection($collectionPoints);
	}

	/**
	 * Get Product And Loyalty Reward Transaction Collection Points Wise
	 * Get Product And Loyalty Reward Transaction Collection Points Wise which are not collected
	 *
	 * @bodyParam collection_point_id int required An id of a collection point. Example: 1
	 * @bodyParam club_id int required An id of a club. Example: 1
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getProductAndLoyaltyRewardTransactionsCollectionPointWise(GetProductAndLoyaltyRewardTransactionsCollectionPointWise $request)
	{
		$clubId = $request->club_id;
		$productAndLoyaltyRewardTransactions = $this->service->getProductAndLoyaltyRewardTransactionsCollectionPointWise($clubId, $request->collection_point_id);

		$getRecentMatch = $this->matchService->getClubRecentMatch($clubId);
		$club = $this->clubService->getClubDetail($clubId);
		$kickOffTime = null;
		if($getRecentMatch) {
			$kickOffTime = Carbon::parse($getRecentMatch->kickoff_time);
		}

		$dataArray = [];
		$dataArray['orders'] = $productAndLoyaltyRewardTransactions;
		$dataArray['half_time_to_go'] = $kickOffTime ? strtoupper($this->service->getRemainingTime($kickOffTime, $club->time_zone, 45)) : null;
		$dataArray['full_time_to_go'] = $kickOffTime ? strtoupper($this->service->getRemainingTime($kickOffTime, $club->time_zone, 105)) : null;

		return response()->json([
			'data' => $dataArray,
		]);
	}

	/**
	 * Change Order Status
	 * Change Order Status
	 *
	 * @bodyParam transaction_id required An id of a transaction. Example: 1
	 * @bodyParam status required, status of a transaction. Example: Ready
	 * @bodyParam type required, type of a transaction. Example: 1
	 * @return \Illuminate\Http\Response
	 */
	public function changeOrderStatus(ChangeOrderStatus $request)
	{
		$request = $request->all();

		$user = JWTAuth::user();
		$staff = $this->staffService->getStaffDetail($user->id);

		$productAndLoyaltyRewardTransactionCollectionData = ProductAndLoyaltyRewardTransactionCollection::
		where('transaction_id', $request['transaction_id'])
			->where('type', $request['type'])
			->first();

		if ($productAndLoyaltyRewardTransactionCollectionData->status == 'Preparing' && $request['status'] == 'Preparing') {
			return response()->json([
				'message' => 'The order is already prepared'
			], 400);
		}

		$updateStatus = $this->service->updateOrderStatus($productAndLoyaltyRewardTransactionCollectionData, $request['status'], $staff->id);
		return response()->json([
			'message' => $request['status'] === 'Preparing' ? 'The order is preparing.' : 'The order is ready for customer.'
		]);
	}

	/**
	 * Scan Order
	 * Scan Order
	 *
	 * @bodyParam type  required, type of record. Example: loyalty_reward or product
	 * @bodyParam transaction_id required, id of a transaction. Example: 1
	 * @return \Illuminate\Http\Response
	 */
	public function scanOrder(ScanOrder $request)
	{
		$request = $request->all();

		$user = JWTAuth::user();
		$staff = $this->staffService->getStaffDetail($user->id);

		$productAndLoyaltyRewardTransactionCollectionData = ProductAndLoyaltyRewardTransactionCollection::with('staff')
								->where('transaction_id', $request['transaction_id'])
								->where('type', $request['type'])
								->first();

		if(!$productAndLoyaltyRewardTransactionCollectionData) {
			return response()->json([
				'message' => 'No such order found.'
			], 404);
		}

		if ($productAndLoyaltyRewardTransactionCollectionData->status == 'Collected') {
			$staffName = ucfirst($productAndLoyaltyRewardTransactionCollectionData->staff->user->first_name) . ' ' . ucfirst($productAndLoyaltyRewardTransactionCollectionData->staff->user->last_name);
			return response()->json([
				'message' => 'This receipt (Order ' . $productAndLoyaltyRewardTransactionCollectionData->id . ') has already been scanned at ' . $productAndLoyaltyRewardTransactionCollectionData->collected_time . ' by staff member ' . $staffName . '.'
			], 400);
		} else {
			$updateStatus = $this->service->updateOrderStatus($productAndLoyaltyRewardTransactionCollectionData, 'Collected', $productAndLoyaltyRewardTransactionCollectionData->staff_id);
			return response()->json([
				'message' => 'The receipt was successfully scanned.'
			]);
		}

	}


}
