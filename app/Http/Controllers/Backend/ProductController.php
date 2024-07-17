<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Models\Club;
use App\Models\Product;
use App\Models\PurchasedProduct;
use App\Services\ProductService;
use App\Services\CollectionPointService;
use Illuminate\Http\Request;
use JavaScript;

/**
 * Product Controller class to handle request.
 */
class ProductController extends Controller
{
    /**
     * The Product service instance.
     *
     * @var service
     */
    public function __construct(ProductService $service, CollectionPointService $collectionPointService)
    {
        $this->service = $service;
        $this->collectionPointService = $collectionPointService;
    }

    /**
     * Display a listing of the resource.
     * @param $club
     * @return \Illuminate\Http\Response
     */
    public function index($club)
    {
        $categoryTypes = config('fanslive.CATEGORY_TYPE');
		$club = Club::where('slug',$club)->first();
		$currencySymbol = config('fanslive.CURRENCY_SYMBOL');
		$currencyIcon =  $currencySymbol[$club->currency];
        return view('backend.products.index', compact('categoryTypes', 'currencyIcon'));
    }

    /**
     * Show the form for creating a product resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($club)
    {
		$club = Club::where('slug',$club)->first();
		$currencySymbol = config('fanslive.CURRENCY_SYMBOL');
        $productStatus = config('fanslive.PUBLISH_STATUS');
        $categoryTypes = config('fanslive.CATEGORY_TYPE');

        // Get all category
        $categories = $this->service->getCategory($club->id);
        //get all collectionPoints
        $collectionPoints = $this->collectionPointService->getCollectionPoints($club->id);
        JavaScript::put([
                'currencySymbol' => $currencySymbol[$club->currency],
            ]);
        return view('backend.products.create', compact('productStatus', 'categories', 'categoryTypes','collectionPoints', 'club', 'currencySymbol'));
    }

    /**
     * Store a product created resource in storage.
     *
     * @param \App\Http\Requests\Product\StoreRequest $request
     * @param  $club
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, $club)
    {
        $clubId = getClubIdBySlug($club);
        $product = $this->service->create(
            $clubId,
            auth()->user(),
            $request->all()
        );

        if ($product) {
            flash('Product created successfully')->success();
        } else {
            flash('Product could not be created. Please try again.')->error();
        }

        return redirect()->route('backend.product.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param  $clubId
     * @param  $product
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $clubId, Product $product)
    {
		$club = Club::where('slug', $clubId)->first();

		$currencySymbol = config('fanslive.CURRENCY_SYMBOL');
        $productStatus = config('fanslive.PUBLISH_STATUS');
        $categoryTypes = config('fanslive.CATEGORY_TYPE');

        // Get all category
        $categories = $this->service->getCategory($club->id);

        // Get only product category
        $productCategory = $this->service->getProductCategory($product);

        // Get product custom option
        $productOptions = $product->productOption()->get()->toArray();

        // Get all collection points.
        $collectionPoints = $this->collectionPointService->getCollectionPoints($club->id);

        // Get only product ccollection point.
        $productCollectionPoint= $product->productCollectionPoint()->pluck('collection_point_id')->toArray();
        JavaScript::put([
                'currencySymbol' => $currencySymbol[$club->currency],
            ]);

        return view('backend.products.edit', compact('productStatus', 'categoryTypes', 'product', 'categories', 'productCategory', 'productOptions','collectionPoints','productCollectionPoint', 'currencySymbol', 'club'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $clubId
     * @param  $product
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($clubId, Product $product)
    {
        $purchasedProduct = PurchasedProduct::where('product_id', $product->id)->get();
        if (count($purchasedProduct) == 0) {
            $productLogoToDelete = $this->service->deleteLogo($product);
            if ($product->delete()) {
                return response()->json(['status'=>'success', 'message'=>'Product deleted successfully']);
            } else {
                return response()->json(['status'=>'error', 'message'=>'Product could not be deleted. Please try again.']);
            }
        } else {
            return response()->json(['status'=>'error', 'message'=>'This product cannot be deleted as transactions have been completed using this product.']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Product\UpdateRequest $request
     * @param  $clubId
     * @param  $product
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $clubId, Product $product)
    {
        $productToUpdate = $this->service->update(
            auth()->user(),
            $product,
            $request->all()
        );

        if ($productToUpdate) {
            flash('Product updated successfully')->success();
        } else {
            flash('Product could not be updated. Please try again.')->error();
        }

        return redirect()->route('backend.product.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Get Product list data.
     *
     * @param \Illuminate\Http\Request $request
     * @param  $clubId
     *
     * @return \Illuminate\Http\Response
     */
    public function getProductData(Request $request, $club)
    {
        $clubId = getClubIdBySlug($club);
        $productList = $this->service->getData(
            $clubId,
            $request->all()
        );

        return $productList;
    }

    /**
     * Get category list based on type.
     *
     * @param \Illuminate\Http\Request $request
     * @param  $clubId
     *
     * @return \Illuminate\Http\Response
     */
    public function getProductCategoryData(Request $request, $club)
    {
        $clubId = getClubIdBySlug($club);
        $getCategoryList = $this->service->getCategory($clubId, $request->type);

        return $getCategoryList;
    }

    /**
     * unset class instance.
     */
    public function __destruct()
    {
        unset($this->service);
    }
}
