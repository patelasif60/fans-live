<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Consumer;
use App\Http\Resources\ProductTransaction\ProductTransaction as ProductTransactionResource;
use App\Http\Resources\ConsumerMembershipPackage\ConsumerMembershipPackage as ConsumerMembershipPackageResource;
use App\Http\Resources\LoyaltyRewardTransaction\LoyaltyRewardTransaction as LoyaltyRewardTransactionResource;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Services\MembershipPackageService;
use App\Services\LoyaltyRewardService;
use JWTAuth;

/**
 * @group Account
 *
 * APIs for Account.
 */
class AccountController extends Controller
{
    /**
	 * Create a news service variable.
	 *
	 * @return void
	 */
	protected $productService;
	protected $membershipPackageService;
    protected $loyaltyRewardService;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(ProductService $productService, MembershipPackageService $membershipPackageService, LoyaltyRewardService $loyaltyRewardService)
	{
		$this->productService = $productService;
		$this->membershipPackageService = $membershipPackageService;
        $this->loyaltyRewardService = $loyaltyRewardService;
	}

    /**
     * Get Orders
     * Get Orders.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function getOrders()
    {
        $user = JWTAuth::user();
        $consumer = Consumer::where('user_id', $user->id)->first();
        $productResponse = $this->productService->getProductOrders($consumer->id);
        $productCollection = ProductTransactionResource::collection($productResponse);

        $membershipPackageResponse = $this->membershipPackageService->getMembershipPackages($consumer->id);
        $membershipPackageCollection = ConsumerMembershipPackageResource::collection($membershipPackageResponse);

        $loyaltyRewardResponse = $this->loyaltyRewardService->getLoyaltyRewardTransactions($consumer->id);
        $loyaltyRewardCollection = LoyaltyRewardTransactionResource::collection($loyaltyRewardResponse);

        $collection = collect([]);
        $dataCollection = $collection->merge($productCollection)->merge($membershipPackageCollection)->merge($loyaltyRewardCollection);
        $data = $dataCollection->sortByDesc('transaction_timestamp')->values()->toArray();
        $response['order_items'] = array_values($data);

        return response()->json([
            'data' => $response,
        ]);
    }
     /**
     * Get user payment account
     * Get user payment account.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserPaymentAccounts()
    {
        $accounts = $this->membershipPackageService->getUserPaymentAccounts(JWTAuth::user());
        return response()->json([
            'data' => $accounts['accounts'],
        ]);
    }
    /**
     * Delete user payment account
     * Delete user payment account.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteUserPaymentAccounts(Request $request)
    {
        $data = $request->all();
        $this->membershipPackageService->deleteUserPaymentAccounts(JWTAuth::user(), $data);
        return response()->json([
            'message' => 'Card has been deleted successully.',
        ]);
    }
}
