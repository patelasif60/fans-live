<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductOption;
use App\Models\ProductTransaction;
use App\Models\PurchasedProduct;
use App\Models\PurchasedProductOption;
use App\Models\CollectionPoint;
use App\Models\ProductCollectionPoint;
use App\Models\ClubOpeningTimeSetting;
use Carbon\Carbon;
use DB;

/**
 * Repository class for model.
 */
class ProductRepository extends BaseRepository
{
	/**
	 * Get Product data.
	 *
	 * @param $clubId
	 * @param $data
	 *
	 * @return mixed
	 */
	public function getData($clubId, $data)
	{
		$productData = DB::table('products')->select(
			'products.id',
			'products.club_id',
			'products.title',
			'products.price',
			'products.status',
			'products.vat_rate',
			DB::raw('IF(group_concat(categories.title SEPARATOR ", ") IS NULL or group_concat(categories.title SEPARATOR ", ") = "", "-", group_concat(categories.title SEPARATOR ", ")) as categories_name')
		)->where('products.club_id', $clubId)->leftJoin('product_category', function ($join) {
				$join->on('products.id', '=', 'product_category.product_id');
			})->leftJoin('categories', function ($join) {
				$join->on('categories.id', '=', 'product_category.category_id');
			})->groupBy('products.id', 'products.club_id', 'products.title', 'products.price', 'products.status');

		if (isset($data['sortby'])) {
			$sortby = $data['sortby'];
			$sorttype = $data['sorttype'];
		} else {
			$sortby = 'products.id';
			$sorttype = 'desc';
		}

		$productData = $productData->orderBy($sortby, $sorttype);

		if (isset($data['title']) && trim($data['title']) != '') {
			$productData->where('products.title', 'like', '%'.$data['title'].'%');
		}

		if (isset($data['category']) && trim($data['category']) != '') {
			$productData->where('product_category.category_id', $data['category']);
		}

		if (isset($data['category_type']) && trim($data['category_type']) != '') {
			$productData->where('categories.type', $data['category_type']);
		}

		$productListArray = [];
		if (!array_key_exists('pagination', $data)) {
			$productData = $productData->paginate($data['pagination_length']);
			$productListArray = $productData;
		} else {
			$productListArray['data'] = $productData->get();
			$productListArray['total'] = count($productListArray['data']);
		}

		$response = $productListArray;

		return $response;
	}

	/**
	 * Handle logic to create a category.
	 *
	 * @param $clubId
	 * @param $user
	 * @param $data
	 *
	 * @return mixed
	 */
	public function create($clubId, $user, $data)
	{
		$product = Product::create([
			'club_id'                     => $clubId,
			'title'                       => $data['title'],
			'short_description'           => $data['short_description'],
			'description'                 => $data['description'],
			'image'                       => $data['logo'],
			'image_file_name'             => $data['logo_file_name'],
			'price'                       => $data['price'],
			'rewards_percentage_override' => $data['rewards_percentage_override'],	'vat_rate' => $data['vat_rate'],
			'status'                      => $data['status'],
			'is_restricted_to_over_age'   => isset($data['is_restricted_to_over_age']) ? 1 : 0,
			'created_by'                  => $user->id,
			'updated_by'                  => $user->id,
		]);

		return $product;
	}

	/**
	 * Handle logic to update a product category.
	 *
	 * @param $data
	 * @param $id
	 *
	 * @return mixed
	 */
	public function createProductCategory($data, $id = null, $action = 0)
	{
		if (!empty($data)) {
			if ($action == 1) {
				$productMappingDelete = ProductCategory::where('product_id', $id)->delete();
			}

			return ProductCategory::insert($data);
		} else {
			return $productMappingDelete = ProductCategory::where('product_id', $id)->delete();
		}
	}

	/**
	 * Handle logic to update a Product pricing Option.
	 *
	 * @param $data
	 * @param $id
	 *
	 * @return mixed
	 */
	public function createProductOption($data, $id = null, $action = 0)
	{
		if (!empty($data)) {
			if ($action == 1) {
				$productMappingDelete = ProductOption::where('product_id', $id)->delete();
			}

			return ProductOption::insert($data);
		} else {
			return $productMappingDelete = ProductOption::where('product_id', $id)->delete();
		}
	}

	/**
	 * Handle logic to update a product.
	 *
	 * @param $user
	 * @param $product
	 * @param $data
	 *
	 * @return mixed
	 */
	public function update($user, $product, $data)
	{
		$product->fill([
			'title'                       => $data['title'],
			'short_description'           => $data['short_description'],
			'description'                 => $data['description'],
			'image'                       => $data['logo'],
			'image_file_name'             => $data['logo_file_name'],
			'price'                       => $data['price'],
			'rewards_percentage_override' => $data['rewards_percentage_override'],	'vat_rate'=> $data['vat_rate'],
			'status'                      => $data['status'],
			'is_restricted_to_over_age'   => isset($data['is_restricted_to_over_age']) ? 1 : 0,
			'updated_by'                  => $user->id,
		]);
		$product->save();

		return $product;
	}

	/**
	 * Get Match data.
	 *
	 * @param $clubId
	 *
	 * @return mixed
	 */
	public function getCategory($clubId, $type = '')
	{
		$category = Category::where('club_id', $clubId);
		if (!empty($type)) {
			$category->where('type', $type);
		}

		return $category->get();
	}

	/**
	 * Handle logic to get only product category.
	 *
	 * @param $product
	 *
	 * @return mixed
	 */
	public function getProductCategory($product)
	{
		$getProductPackage = $product::with(['productCategory'])->where('id', $product->id)->get();

		return $getProductPackage;
	}

	/**
	 * Handle logic to update product details.
	 *
	 * @param $data
	 * @param $productTransactionId
	 */
	public function updateProductPurchase($data, $product)
	{
		$product->collection_point_id = $data['collection_point_id'];
		$product->selected_collection_time = $data['selected_collection_time'];
		$product->collection_time = $data['collection_time'];
		$product->status = $data['status'];
		$product->psp_reference_id = $data['psp_reference_id'];
		$product->payment_method = $data['payment_method'];
		$product->status_code = $data['status_code'];
		$product->psp = $data['psp'];
		$product->psp_account = $data['psp_account'];
		$product->transaction_timestamp = $data['transaction_timestamp'];
		$product->save();
		return $product;
	}

	/**
	 * Handle logic to create a new product.
	 *
	 * @param $data
	 */
	public function createProductPurchase($data)
	{
		$product = new ProductTransaction();
		$product->match_id = $data['match_id'];
		$product->club_id = $data['club_id'];
		$product->consumer_id = $data['consumer_id'];
		$product->price = $data['price'];
		$product->currency = $data['currency'];
		$product->type = $data['type'];
		$product->transaction_reference_id = $data['transaction_reference_id'];
		$product->card_details = json_encode($data['card_details']);
		$product->payment_status = $data['payment_status'];
		$product->custom_parameters = json_encode($data['custom_parameters']);
		$product->save();
		return $product;
	}

	/**
	 * Save purchased product.
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function savePurchasedProduct($data)
	{
		$purchasedProduct = new PurchasedProduct();
		$purchasedProduct->product_transaction_id = $data['product_transaction_id'];
		$purchasedProduct->product_id                           = $data['product_id'];
		$purchasedProduct->quantity                             = $data['quantity'];
		$purchasedProduct->vat_rate                             = $data['vat_rate'];
		$purchasedProduct->per_quantity_price                   = $data['per_quantity_price'];
		$purchasedProduct->per_quantity_actual_price			= $data['per_quantity_actual_price'];
		$purchasedProduct->per_quantity_additional_options_cost = $data['per_quantity_additional_options_cost'];
		$purchasedProduct->total_price                          = $data['total_price'];
		$purchasedProduct->transaction_timestamp                = $data['transaction_timestamp'];
		$purchasedProduct->special_offer_id                     = $data['special_offer_id'];
		$purchasedProduct->special_offer_discount_type          = $data['special_offer_discount_type'];
		$purchasedProduct->special_offer_discount               = $data['special_offer_discount'];
		$purchasedProduct->save();
		return $purchasedProduct;
	}

	/**
	 * Save purchased product options.
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function savePurchasedProductOptions($data)
	{
		$purchasedProductOption = new PurchasedProductOption();
		$purchasedProductOption->purchased_product_id = $data['purchased_product_id'];
		$purchasedProductOption->product_option_id = $data['product_option_id'];
		$purchasedProductOption->save();
		return $purchasedProductOption;
	}

	/**
	 * Handle logic to update a product collection point.
	 *
	 * @param $data
	 * @param $id
	 *
	 * @return mixed
	 */
	public function createProductCollectionPoint($data)
	{
		return ProductCollectionPoint::insert($data);
	}
	public function getAllCollectionPoints()
	{
		$data = CollectionPoint::select('id')->get();
		return $data;
	}

	/**
	 * Get product transactions.
	 *
	 * @param $consumerId
	 * @param $type
	 *
	 * @return mixed
	 */
	public function getProductOrders($consumerId, $type)
	{
		if($type == "") {
			return ProductTransaction::where('consumer_id', $consumerId)->where('status', 'successful')->orderBy('id', 'desc')->get();
		} else {
			return ProductTransaction::where('consumer_id', $consumerId)->where('status', 'successful')->where('type', $type)->orderBy('id', 'desc')->get();
		}
	}

	/**
	 * Get product transactions query.
	 *
	 * @param $clubId
	 * @param $data
	 *
	 * @return mixed
	 */
	public function getProductTransactionQueryForTransactions()
	{
		$productTransactionQuery = DB::table('product_transactions')->select(
			'product_transactions.id as id',
			'clubs.name as club',
			'clubs.time_zone as club_time_zone',
			'product_transactions.consumer_id as consumer_id',
			'product_transactions.club_id as club_id',
			//'product_transactions.payment_type as payment_type',
			'product_transactions.payment_brand as payment_brand',
			'product_transactions.price as price',
			'product_transactions.fee as fee',
			'product_transactions.currency as currency',
			'product_transactions.status as status',
			'product_transactions.payment_status as payment_status',
			'product_transactions.transaction_timestamp as transaction_timestamp',
			'users.email as email',
			DB::raw('CASE WHEN product_transactions.type IS NULL THEN "product" ELSE product_transactions.type END as transaction_type'),
			DB::raw('ROUND(product_transactions.price*(product_transactions.fee/100),2) as fee_amount'),
			DB::raw('CONCAT(users.first_name," ",users.last_name) as name')
		)
		->leftJoin('consumers', 'consumers.id', '=', 'product_transactions.consumer_id')
		->leftJoin('users', 'users.id', '=', 'consumers.user_id')
		->leftJoin('clubs', 'clubs.id', '=', 'product_transactions.club_id')
		;
		return $productTransactionQuery;
	}

	/**
	 * Get product transactions payment brand.
	 *
	 * @param $clubId
	 * @param $data
	 *
	 * @return mixed
	 */
	public function getPaymentCardType()
	{
		$paymentBrands = DB::table('product_transactions')->select('payment_brand')->groupBy('payment_brand')->get();
		return $paymentBrands;
	}

	/**
	 * Get product transactions by collection point
	 *
	 * @param $clubId
	 * @param $collectionPointId
	 *
	 * @return mixed
	 */
	public function getProductTransactionsForCollectionPoint($clubId, $collectionPointId)
	{
		$productTransactions = ProductTransaction::with('purchasedProducts')
			->join('product_and_loyalty_reward_transaction_collections', 'product_and_loyalty_reward_transaction_collections.transaction_id', '=', 'product_transactions.id')
			->select('*', 'product_transactions.id as id', 'product_transactions.psp_reference_id as transaction_id', 'product_transactions.type as type', 'product_and_loyalty_reward_transaction_collections.type as transaction_type')
			->where('product_and_loyalty_reward_transaction_collections.type', 'product')
			->where('product_transactions.collection_point_id', $collectionPointId)
			->where('product_transactions.club_id', $clubId)
			->where('product_and_loyalty_reward_transaction_collections.status', '!=', 'Collected')
			->get();
		return $productTransactions;
	}

	/**
	 * Handle logic to get product for age restricted consumer.
	 *
	 * @param $isAgeRestricted
	 * @param $productIds=[]
	 *
	 * @return mixed
	 */
	public function getProductsForAgeRestrictedConsumer($isAgeRestricted = FALSE, $productIds = [])
	{
		$products = Product::when($isAgeRestricted, function($q){
								$q->where('is_restricted_to_over_age','=',0);
							});
		if ($productIds) {
			$products->whereIn('id', $productIds);
		}
		return $products->get();
	}
	/**
	 * Handle logic to get product transaction data.
	 * @param transactionReferenceId
	 *
	 * @return mixed
	 */
	public function getProductTransactionData($transactionReferenceId)
	{
		return ProductTransaction::where('transaction_reference_id',$transactionReferenceId)->get()->first();
	}
	/**
    * Get all Data
    */
    public function getAllPurchaseProduct()
    {
    	return ProductTransaction::all();
    }
}
