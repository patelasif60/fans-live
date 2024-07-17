<?php


namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Consumer;
use App\Services\ProductService;
use App\Services\CategoryService;
use App\Services\StadiumBlockService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Category\GetCategoryRequest;
use App\Http\Requests\Api\Category\GetCategoriesBasedOnSeatRequest;
use App\Http\Resources\Category\Category as CategoryResource;
use App\Http\Resources\SpecialOffer\SpecialOffer as SpecialOfferResource;
use App\Models\Category;
use App\Models\StadiumBlock;
use App\Models\StadiumBlockSeat;
use App\Models\ProductCollectionPoint;
use App\Services\ClubAppSettingService;
use Illuminate\Http\Request;
use JWTAuth;

/**
 * @group Category
 *
 * APIs for Category.
 */
class CategoryController extends Controller
{
    /**
      * Create a product service variable.
      *
      * @return void
    */
    protected $productService;
    protected $categoryService;
    protected $stadiumBlockService;
    protected $clubAppSettingService;

    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct(ProductService $productService, CategoryService $categoryService, StadiumBlockService $stadiumBlockService, ClubAppSettingService $clubAppSettingService)
    {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
        $this->stadiumBlockService = $stadiumBlockService;
        $this->clubAppSettingService = $clubAppSettingService;
    }

	/**
     * Get Categories
     * Get Categories.
     *
     * @bodyParam club_id int required An id of a club. Example: 1
     * @bodyParam type string required A type of category. Example: 'abc'
     *
     *
     * @return mixed
    */
    public function getCategories(GetCategoryRequest $request)
    {
        $user = JWTAuth::user();
        $consumer = Consumer::where('user_id', $user->id)->first();
        $clubTimings = $this->clubAppSettingService->getClubOpeningTimeSetting($consumer->club_id);

        $productCollectionPointIds = $this->productService->getProductIdsOfCollectionPoints($clubTimings, $request['type']);

        $categoryData = $this->categoryService->getCategories($request['club_id'], $request['type'], $productCollectionPointIds, $consumer->age);

        $specialOffers = $this->categoryService->getSpecialOffers($request['club_id'], $request['type'], "", $consumer->age);

        $categoryCollection = CategoryResource::collection($categoryData);
        $specialOfferCollection = SpecialOfferResource::collection($specialOffers);

        $collection = collect([]);
        $dataCollection = $collection->merge($specialOfferCollection)->merge($categoryCollection);

        $response['categories'] = $dataCollection;

        return response()->json([
            'data' => $response,
        ]);
    }

    /**
     * Get categories based on seat
     * Find product categories based on seat
     *
     * @bodyParam club_id int required An id of a club.
     * @bodyParam type string required.
     * @bodyParam block_id int required An id of a block.
     * @bodyParam seat int required, number of a Seat. Example : A15 (combination of row and seat)
     *
     * @return \Illuminate\Http\Response
     */
    public function getCategoriesBasedOnSeat(GetCategoriesBasedOnSeatRequest $request)
    {
        $stadiumBlockSeat = $this->stadiumBlockService->getStadiumBlockSeats($request['seat'], $request['block_id']);
        if (!isset($stadiumBlockSeat)) {
            return  response()->json([
                        'message' => "Seat not found.",
                    ], 404);
        }

        $user = JWTAuth::user();
        $consumer = Consumer::where('user_id', $user->id)->first();

        $stadiumBlocks = $this->stadiumBlockService->stadiumBlock($request['block_id']);

        $products = $this->categoryService->getProductsBasedOnSeat($request['block_id'], $stadiumBlocks, $consumer->age);

        $categories = $this->categoryService->getCategoryData($request['club_id'], $request['type'], $products, $consumer->age);
        $categoryCollection = CategoryResource::collection($categories);

        $specialOffers = $this->categoryService->getSpecialOffersData($request['club_id'], $request['type'], $products, $consumer->age);
        $specialOfferCollection = SpecialOfferResource::collection($specialOffers);

        $collection = collect([]);
        $dataCollection = $collection->merge($categoryCollection)->merge($specialOfferCollection);

        $response['categories'] = $dataCollection;

        return response()->json([
            'data' => $response,
        ]);

    }

}
