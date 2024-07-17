<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoyaltyReward\GetLoyaltyRewardRequest;
use App\Http\Requests\Api\LoyaltyReward\PurchaseLoyaltyRewardRequest;
use App\Http\Requests\Api\LoyaltyReward\GetLoyaltyRewardBasedOnSeatRequest;
use App\Models\Consumer;
use App\Models\LoyaltyReward;
use App\Models\LoyaltyRewardPointHistory;
use App\Services\CategoryService;
use App\Services\CollectionPointService;
use App\Services\LoyaltyRewardService;
use App\Services\LoyaltyRewardPointHistoryService;
use App\Services\StadiumBlockService;
use App\Services\ClubAppSettingService;
use App\Services\MatchService;
use App\Http\Resources\LoyaltyReward\LoyaltyReward as LoyaltyRewardResource;
use App\Http\Resources\LoyaltyRewardPointHistory\LoyaltyRewardPointHistory as LoyaltyRewardPointHistoryResource;
use App\Http\Resources\LoyaltyRewardTransaction\LoyaltyRewardTransaction as LoyaltyRewardTransactionResource;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use JWTAuth;
use App\Services\UserService;
use App\Jobs\SendLoyaltyRewardTransactionEmail;
//use App\Models\LoyaltyRewardTransaction;
/**
 * @group Loyalty Reward
 *
 * APIs for Loyalty Reward.
 */
class LoyaltyRewardController extends Controller
{
	/**
	 * Create a loyalty reward service variable.
	 *
	 * @return void
	 */
	protected $service;
	protected $productService;
	protected $loyaltyRewardPointHistoryService;
	protected $collectionPointService;
	protected $categoryService;
	protected $stadiumBlockService;
	protected $slubAppSettingService;
	protected $matchService;
	protected $userService;
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(LoyaltyRewardService $service, ProductService $productService, LoyaltyRewardPointHistoryService $loyaltyRewardPointHistoryService, CollectionPointService $collectionPointService, CategoryService $categoryService, StadiumBlockService $stadiumBlockService, ClubAppSettingService $clubAppSettingService, MatchService $matchService,UserService $userService)
	{
		$this->service = $service;
		$this->productService = $productService;
		$this->loyaltyRewardPointHistoryService = $loyaltyRewardPointHistoryService;
		$this->collectionPointService = $collectionPointService;
		$this->categoryService = $categoryService;
		$this->stadiumBlockService = $stadiumBlockService;
		$this->clubAppSettingService = $clubAppSettingService;
		$this->matchService = $matchService;
		$this->userService = $userService;
	}

	/**
	 * Get Loyalty reward with options.
	 * Get Loyalty reward with options.
	 *
	 * @bodyParam club_id int required An id of a Club. Example: 1
	 * @param int $request
	 * @return void
	 */
	public function getLoyaltyRewardProducts(GetLoyaltyRewardRequest $request)
	{
		$request = $request->all();
		$clubTimings = $this->clubAppSettingService->getClubOpeningTimeSetting($request['club_id']);

		$matches = $this->service->getClubLoyaltyPointRewardMatch($request['club_id'], $clubTimings);

		$isLoyaltyRewardSaleAvailable = false;
		if ($matches->count() > 0) {
			$isLoyaltyRewardSaleAvailable = true;
		}

		$loyaltyRewardIds = $this->service->getLoyaltyRewardIdsOfCollectionPoints();

		$user = JWTAuth::user();
		$consumer = Consumer::where('user_id', $user->id)->first();
		$loyaltyRewardProducts = $this->service->getLoyaltyRewardForAgeRestrictedConsumer($consumer->club->id, $consumer, $loyaltyRewardIds);
		$loyaltyRewardProducts = LoyaltyRewardResource::collection($loyaltyRewardProducts);

		$response['is_loyalty_reward_sale_available'] = $isLoyaltyRewardSaleAvailable;
		$response['is_ticket_purchased_from_app'] = $this->productService->getTicketPurchasedFromAppStatus('loyaltyreward', $clubTimings);
		$response['loyalty_reward_products'] = $loyaltyRewardProducts;

		return response()->json([
			'data' => $response,
		]);
	}

	/**
	 * Get purchase loyalty reward product.
	 * Get purchase loyalty reward product.
	 *
	 * @bodyParam club_id int required An id of a Club. Example: 1
	 * @param int $request
	 * @return void
	 */
	public function purchaseLoyaltyRewardProduct(PurchaseLoyaltyRewardRequest $request)
	{
		$data = $request->all();
		$user = JWTAuth::user();
		$consumer = Consumer::where('user_id', $user->id)->first();
		$clubTimings = $this->clubAppSettingService->getClubOpeningTimeSetting($consumer->club_id);

		$match = $this->matchService->getConsumerMatch($consumer, 'loyaltyreward', $clubTimings);
		if (!isset($match)) {
			return response()->json([
				'message' => 'No match found.'
			], 400);
		}

		$loyaltyRewardProducts = json_decode($data['loyalty_reward_products']);
		$loyaltyRewardProductIds = array_column($loyaltyRewardProducts, 'loyalty_reward_product_id');
		$loyaltyRewardProducts = $this->service->getLoyaltyRewardForAgeRestrictedConsumer($consumer->club->id ,$consumer, $loyaltyRewardProductIds);
		if (count($loyaltyRewardProducts) != count($loyaltyRewardProductIds)) {
			return response()->json([
				'message' => 'Loyalty reward not found.'
			], 400);
		}

		$currentBalance = $this->loyaltyRewardPointHistoryService->getConsumerLoyaltyRewardPointBalance($consumer->id);

		$loyaltyRewardData = $this->service->calculateLoyaltyReward($data['loyalty_reward_products']);

		$totalPoints = $loyaltyRewardData['totalPoints'];

		if($currentBalance < $totalPoints) {
			return response()->json([
				'message' => 'Low balance.'
			], 400);
		}

		$data['collection_point_id'] = $this->productService->getProductCollectionPointId($match, $consumer, $data);

		$data['loyaltyRewards'] = json_encode($loyaltyRewardData['loyaltyRewards']);

		$loyaltyRewardTransaction = $this->service->createLoyaltyRewardPurchase($consumer, $data, $totalPoints, $match);

		$this->service->savePurchasedLoyaltyReward($loyaltyRewardTransaction->id, $data);

		$this->service->updateReceiptNumberOfLoyaltyReward($consumer, $loyaltyRewardTransaction);

		$loyaltyRewardPointHistory = $this->loyaltyRewardPointHistoryService->createLoyaltyRewardPointHistory($consumer, $loyaltyRewardTransaction->id, $totalPoints, 'loyalty_reward');

		$collectionPoint = $this->collectionPointService->createProductAndLoyaltyRewardTransaction($loyaltyRewardTransaction->id, 'loyalty_reward');

		//$loyaltyRewardTransaction = LoyaltyRewardTransaction::find(12);

		$clubAdmins = $this->userService->clubAdmin($consumer->club_id);
		$superAdmins = $this->userService->superAdmin();
		SendLoyaltyRewardTransactionEmail::dispatch($loyaltyRewardTransaction,$consumer,$clubAdmins,$superAdmins)->onQueue(config('fanslive.TRANSACTION_EMAILS'));
		return new LoyaltyRewardTransactionResource($loyaltyRewardTransaction);

	}

	/**
	 * Get purchase loyalty reward history.
	 * Get purchase loyalty reward history.
	 *
	 * @return void
	 */
	public function getLoyaltyRewardHistory(Request $request)
	{
		$user = JWTAuth::user();
		$consumer = Consumer::where('user_id', $user->id)->first();
		$loyaltyRewardPointHistory = LoyaltyRewardPointHistory::where('consumer_id', $consumer->id)->orderBy('created_at', 'desc')->get();
		return LoyaltyRewardPointHistoryResource::collection($loyaltyRewardPointHistory);
	}

	/**
	 * Get loyalty rewards based on seat
	 * Find loyalty rewards based on seat
	 *
	 * @bodyParam club_id int required An id of a club.
	 * @bodyParam block_id int required An id of a block.
	 * @bodyParam seat int required, number of a Seat. Example : A15 (combination of row and seat)
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getLoyaltyRewardBasedOnSeat(GetLoyaltyRewardBasedOnSeatRequest $request)
	{
		$stadiumBlockSeat = $this->stadiumBlockService->getStadiumBlockSeats($request['seat'], $request['block_id']);

		if (!isset($stadiumBlockSeat)) {
			return  response()->json([
				'message' => "Seat not found.",
			], 404);
		}

		$stadiumBlock = $this->stadiumBlockService->stadiumBlock($request['block_id']);

		$loyaltyRewards = $this->service->getLoyaltyRewardBasedOnSeat($request['block_id'], $stadiumBlock);

        $loyaltyRewardCollection = LoyaltyRewardResource::collection($loyaltyRewards);

		return $loyaltyRewardCollection;

	}

}
