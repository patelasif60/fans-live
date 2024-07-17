<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Product\GetProductRequest;
use App\Http\Requests\Api\Product\SearchProductRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Product\PaymentRequest;
use App\Http\Requests\Api\Product\MakePaymentRequest;
use App\Http\Requests\Api\Product\ValidatePaymentRequest;
use App\Http\Requests\Api\Product\OrderRequest;
use App\Http\Requests\Api\Product\ProductConfigurationRequest;
use App\Http\Resources\ProductTransaction\ProductTransaction as ProductTransactionResource;
use App\Http\Resources\MembershipPackage\MembershipPackage as MembershipPackageResource;
use App\Models\ClubLoyaltyPointSetting;
use App\Models\Consumer;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Category;
use App\Models\TicketTransaction;
use App\Services\LoyaltyRewardPointHistoryService;
use App\Services\ProductService;
use App\Services\CategoryService;
use App\Services\CollectionPointService;
use App\Services\ClubAppSettingService;
use App\Services\MatchService;
use Illuminate\Http\Request;
use App\Http\Resources\Product\Product as ProductResource;
use App\Http\Resources\Category\Category as CategoryResource;
use App\Http\Resources\SpecialOffer\SpecialOffer as SpecialOfferResource;
use Carbon\Carbon;
use JWTAuth;
use App\Jobs\SendProductTransactionEmail;
use App\Services\UserService;
//use App\Models\ProductTransaction;

/**
 * @group Product
 *
 * APIs for Product.
 */
class ProductController extends Controller
{

	/**
	 * Create a news service variable.
	 *
	 * @return void
	 */
	protected $service;
	protected $categoryService;
	protected $loyaltyRewardPointHistoryService;
	protected $collectionPointService;
	protected $clubAppSettingService;
	protected $matchService;
	protected $userService;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(ProductService $service, CategoryService $categoryService, LoyaltyRewardPointHistoryService $loyaltyRewardPointHistoryService, CollectionPointService $collectionPointService, ClubAppSettingService $clubAppSettingService, MatchService $matchService,UserService $userService)
	{
		$this->service = $service;
		$this->categoryService = $categoryService;
		$this->loyaltyRewardPointHistoryService = $loyaltyRewardPointHistoryService;
		$this->collectionPointService = $collectionPointService;
		$this->clubAppSettingService = $clubAppSettingService;
		$this->matchService = $matchService;
		$this->userService = $userService;
	}

	/**
	 * Get Category Products or Special Offer Products
	 * Get Category Products or Special Offer Products.
	 *
	 * @bodyParam category_id int required An id of a category. Example: 1
	 * @bodyParam related_to string required. Example: category or special_offer
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getCategoryProducts(GetProductRequest $request)
	{
		$user = JWTAuth::user();
		$consumer = Consumer::where('user_id', $user->id)->first();
		$clubTimings = $this->clubAppSettingService->getClubOpeningTimeSetting($consumer->club_id);

        $request = $request->all();
        $membershipPackages = null;
        $specialOfferAccessibleFor = null;

		if ($request['related_to'] == "special_offer") {

			$specialOffers = $this->categoryService->getSpecialOffers($consumer->club_id, "", "", $consumer->age)->where('id', $request['category_id'])->first();

			if(isset($specialOffers)) {
	            $data = $this->service->getSpecialOfferProducts($consumer, $specialOffers);
	            if($data['membershipPackages'] != null) {
	                $membershipPackages = new MembershipPackageResource($data['membershipPackages']);
	            }

	            $specialOfferAccessibleFor = $data['membershipPackagesName'];
	        } else {
				$data['isSpecialOfferAccessible'] = false;
	        }
        } else {
            $data = $this->service->getCategoryProducts($request['category_id'], $clubTimings, $consumer->age);
        }

        $response['is_special_offer_accessible'] = $data['isSpecialOfferAccessible'];
        $response['special_offer_accessible_for'] = $specialOfferAccessibleFor;
        $response['special_offer_accessible_membership_detail'] = $membershipPackages;
        if(isset($data['products']) && count($data['products']) > 0) {
			$response['products'] = ProductResource::collection($data['products']);
        } else {
			$response['products'] = null;
        }

		return response()->json([
			'data' => $response,
		]);
	}

	/**
	 * Search product
	 * Search product
	 *
	 * @bodyParam category_type string required A type of a category. Example: 'merchandise'
	 * @bodyParam search_param string required search parameter. Example: 'abc'
	 */
	public function getSearchProducts(SearchProductRequest $request)
	{
		$request = $request->all();
		$user = JWTAuth::user();
		$consumer = Consumer::where('user_id', $user->id)->first();
		$clubTimings = $this->clubAppSettingService->getClubOpeningTimeSetting($consumer->club_id);

		$product = Product::join('product_category', 'product_category.product_id', '=', 'products.id')->join('categories', 'product_category.category_id', '=', 'categories.id')
			->whereIn('products.id', $this->service->getProductIdsOfCollectionPoints($clubTimings, $request['category_type']))
			->where('products.club_id', $consumer->club_id)
			->where('categories.type', $request['category_type'])
			->where(function ($query) use ($request) {
				return $query->where('products.title', 'like', "%" . $request['search_param'] . "%")
					->orwhere('short_description', 'like', "%" . $request['search_param'] . "%")
					->orwhere('description', 'like', "%" . $request['search_param'] . "%")
					->orwhere('categories.title', 'like', "%" . $request['search_param'] . "%");
			});
		if($consumer->age < config('fanslive.IS_RESTRICTED_TO_OVER_AGE')) {
            $product = $product->where('products.is_restricted_to_over_age', 0)->where('categories.is_restricted_to_over_age', 0);
        }

		$product = $product->select('products.*')->get();
		return ProductResource::collection($product);
	}
	/**
	 * Get Orders
	 * Get Orders.
	 *
	 * @bodyParam type string required
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getProductOrders(OrderRequest $request)
	{
		$data = $request->all();
		$user = JWTAuth::user();
		$consumer = Consumer::where('user_id', $user->id)->first();
		$productResponse = $this->service->getProductOrders($consumer->id, $request->type);
		return ProductTransactionResource::collection($productResponse);
	}

	/**
	 * Get Product Configurations
	 * Get Product Configurations
	 *
	 * @bodyParam club_id string required
	 * @bodyParam type string required
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getProductConfigurations(ProductConfigurationRequest $request)
	{
		$data = $request->all();
		$user = JWTAuth::user();
		$consumer = Consumer::where('user_id', $user->id)->first();
		$clubTimings = $this->clubAppSettingService->getClubOpeningTimeSetting($data['club_id']);

		$match = $this->service->getClubMatch($data['club_id'], $data['type'], $clubTimings);

		$isProductSaleAvailable = false;
		if ($match->count() > 0) {
			$isProductSaleAvailable = true;
		}

		$productCollectionPointIds = $this->service->getProductIdsOfCollectionPoints($clubTimings, $request['type']);

		$categoryData = $this->categoryService->getCategories($consumer->club->id, $request['type'], $productCollectionPointIds, $consumer->age);

		$specialOffers = $this->categoryService->getSpecialOffers($consumer->club->id, $request['type'], "", $consumer->age);

		$categoryCollection = CategoryResource::collection($categoryData);
		$specialOfferCollection = SpecialOfferResource::collection($specialOffers);

		$collection = collect([]);
		$dataCollection = $collection->merge($specialOfferCollection)->merge($categoryCollection);

		$response['is_product_sale_available'] = $isProductSaleAvailable;
		$response['is_ticket_purchased_from_app'] = $this->service->getTicketPurchasedFromAppStatus($data['type'], $clubTimings);
		$response['categories'] = $dataCollection;
		$response['match_half_time'] = $match->count() > 0 ? Carbon::parse($match[0]->kickoff_time)->addMinutes(45)->format("Y-m-d H:i:s") : null;

		return response()->json([
			'data' => $response,
		]);

	}
	/**
	 * Make payment.
	 * Make payment for product purchase.
	 *
	 * @bodyParam consumer_card_id int required The id of card. Example: 1
	 * @bodyParam products json required A products data. Example: {"consumer_card_id":111,"currency":"EUR","type"="food_and_drink","selected_collection_time":"half_time","products":[{"product_id":1,"quantity":1,"transaction_timestamp":"2020-08-26 14:00:00","product_options":[{"id":1}]},{"product_id":2,"quantity":2,"per_quantity_price":10,"total_price":20,"transaction_timestamp":"2020-08-26 14:00:00","product_options":[{"id":2}]}]}
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function makeProductPayment(MakePaymentRequest $request)
	{
		$user = JWTAuth::user();
		$consumer = Consumer::where('user_id', $user->id)->first();
		$data = $request->all();
		$clubTimings = $this->clubAppSettingService->getClubOpeningTimeSetting($consumer->club_id);
		$matches = $this->service->getClubMatch($consumer->club_id, $data['type'], $clubTimings);
		if ($matches->count() == 0) {
			return response()->json([
				'message' => 'Our shop is currently not taking orders.<br> We will be open again soon.'
			], 400);
		}

		$match = $this->matchService->getConsumerMatch($consumer, $data['type'], $clubTimings);
		if (!isset($match)) {
			return response()->json([
				'message' => 'No match found.'
			], 400);
		}

		$productsArr = json_decode($data['products'], TRUE);
		$productIds = array_unique(array_column($productsArr, 'product_id'));
		$products = $this->service->getProductsForAgeRestrictedConsumer($consumer, $productIds);
		if (count($products) != count($productIds)) {
			return response()->json([
				'message' => 'No product found.'
			], 400);
		}
		$productData = $this->service->calculateProductTransactionPrice($data['products']);
		$data['collection_point_id'] = $this->service->getProductCollectionPointId($match, $consumer,$data);


		$data['products'] = json_encode($productData['products']);
		$totalPrice = $productData['totalPrice'];

		$productTransaction = $this->service->updateProductPurchase($data, $match ,$consumer ,$totalPrice);

		if($data['transaction_summary']['data']['status']=='failed')
		{
			return $productTransaction;
		}
		$this->service->savePurchasedProduct($productTransaction->id, $data);
		$this->service->updateReceiptNumberOfProduct($consumer, $productTransaction);
		$earnedLoyaltyPoints = $this->service->getEarnedLoyaltyPoints($consumer, $productTransaction);

		$loyaltyRewardPointHistory = $this->loyaltyRewardPointHistoryService->createLoyaltyRewardPointHistory($consumer, $productTransaction->id, $earnedLoyaltyPoints, $productTransaction->type);

		$collectionPoint = $this->collectionPointService->createProductAndLoyaltyRewardTransaction($productTransaction->id, 'product');

		//$productTransaction = ProductTransaction::find(1);
		//dd($productTransaction->purchasedProducts);
		$clubAdmins = $this->userService->clubAdmin($consumer->club_id);
		$superAdmins = $this->userService->superAdmin();
		SendProductTransactionEmail::dispatch($productTransaction,$consumer,$clubAdmins,$superAdmins)->onQueue(config('fanslive.TRANSACTION_EMAILS'));
		return new ProductTransactionResource($productTransaction);
	}

	/**
     * validate  product Payment
     */
    public function validateProductPayment(ValidatePaymentRequest $request)
    {
    	$user = JWTAuth::user();
		$consumer = Consumer::where('user_id', $user->id)->first();
		$data = $request->all();

    	return $this->service->validateProductPayment($data, $consumer);
    }
}
