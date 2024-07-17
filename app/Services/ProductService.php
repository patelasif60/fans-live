<?php

namespace App\Services;

use DB;
use JWTAuth;
use App\Models\Match;
use App\Models\Consumer;
use App\Models\TicketTransaction;
use App\Models\Product;
use App\Models\ProductCollectionPoint;
use App\Models\CollectionPoint;
use App\Models\ProductOption;
use App\Models\Category;
use App\Models\SpecialOffer;
use App\Models\MembershipPackage;
use App\Models\ClubLoyaltyPointSetting;
use App\Models\PurchasedProduct;
use App\Models\StadiumBlock;
use App\Repositories\ProductRepository;
use App\Repositories\CollectionPointRepository;
use App\Repositories\SpecialOfferRepository;
use App\Services\ACIWorldWide\Client;
use App\Services\ClubAppSettingService;
use App\Services\MatchService;
use File;
use Storage;
use Image;
use QrCode;
use Carbon\Carbon;

/**
 * Product class to handle operator interactions.
 */
class ProductService
{
    /**
     * The product repository instance.
     *
     * @var repository
     */
    protected $repository;

    /**
     * The collection point repository instance.
     *
     * @var repository
     */
    protected $collectionPointRepository;

    /**
     * Create a ACIWorldWide client API variable.
     *
     * @return void
     */
    protected $apiclient;

    /**
     * The product image path.
     *
     * @var logoPath
     */
    protected $logoPath;

    /**
     * The special offer repository instance.
     *
     * @var repository
     */
    protected $specialOfferRepository;

    /**
     * Create a new service instance.
     *
     * @param ProductRepository $repository
     */

    /**
     * Create a QRcode path  variable.
     *
     * @return void
     */
    protected $productTransactionQrcodePath;

    public function __construct(ProductRepository $repository, Client $apiclient, CollectionPointRepository $collectionPointRepository, MatchService $matchService, ClubAppSettingService $clubAppSettingService, SpecialOfferRepository $specialOfferRepository)
    {
        $this->repository = $repository;
        $this->apiclient = $apiclient;
        $this->collectionPointRepository = $collectionPointRepository;
        $this->logoPath = config('fanslive.IMAGEPATH.product_logo');
        $this->matchService = $matchService;
        $this->clubAppSettingService = $clubAppSettingService;
        $this->specialOfferRepository = $specialOfferRepository;
        $this->productTransactionQrcodePath = config('fanslive.IMAGEPATH.product_transaction_qrcode');
    }

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
        $product = $this->repository->getData($clubId, $data);
        return $product;
    }

    /**
     * Handle logic to create a product.
     *
     * @param $clubId
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($clubId, $user, $data)
    {
        if (isset($data['logo'])) {
            $logo = uploadImageToS3($data['logo'], $this->logoPath);
            $data['logo'] = $logo['url'];
            $data['logo_file_name'] = $logo['file_name'];
        } else {
            $data['logo'] = null;
            $data['logo_file_name'] = null;
        }

        $product = $this->repository->create($clubId, $user, $data);
        if (!empty($product)) {

            // Insert product category
            $this->createProductCategory($product, $data);

            // Insert product pricing option
            $this->createProductOption($product, $data);

            //Insert product collection point()
            $this->createProductCollectionPoint($product,$clubId);
        }

        return $product;
    }

    /**
     * Handle logic to update a given product.
     *
     * @param $user
     * @param $product
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $product, $data)
    {
        $disk = Storage::disk('s3');
        if (isset($data['logo'])) {
            $existingLogo = $this->logoPath.$product->image_file_name;
            $disk->delete($existingLogo);
            $logo = uploadImageToS3($data['logo'], $this->logoPath);
            $data['logo'] = $logo['url'];
            $data['logo_file_name'] = $logo['file_name'];
        } else {
            $data['logo'] = $product->image;
            $data['logo_file_name'] = $product->image_file_name;
        }

        $productToUpdate = $this->repository->update($user, $product, $data);

        if (!empty($productToUpdate)) {
            // Insert product category
            $this->createProductCategory($productToUpdate, $data, 1);

            // Insert product pricing option
            $this->createProductOption($productToUpdate, $data, 1);

            //Insert product collection point()
            //$this->createProductCollectionPoint($product, $data, 1);
        }

        return $productToUpdate;
    }

    /**
     * Handle logic to create or update a product category.
     *
     * @param $product
     * @param $data
     * @param $action
     *
     * @return mixed
     */
    protected function createProductCategory($product, $data, $action = 0)
    {
        $dbFields = [];
        $id = null;

        if (!empty($data['category'])) {
            foreach ($data['category'] as $key => $val) {
                $dbFields[] = [
                    'product_id'  => $product->id,
                    'category_id' => $key,
                ];
            }
        }

        if ($action == 1) {
            $id = $product->id;
        }

        //For product category
        $this->repository->createProductCategory($dbFields, $id, $action);

        return $product;
    }

    /**
     * Handle logic to create or update a product pricing options.
     *
     * @param $product
     * @param $data
     * @param $action
     *
     * @return mixed
     */
    protected function createProductOption($product, $data, $action = 0)
    {
        $dbFields = [];
        $id = null;

        if (!empty($data['additional_cost']) && !empty($data['name'])) {
            if (count($data['additional_cost']) == count($data['name'])) {
                foreach ($data['additional_cost'] as $key => $val) {
                    $dbFields[] = [
                        'product_id'      => $product->id,
                        'additional_cost' => $data['additional_cost'][$key],
                        'name'            => $data['name'][$key],
                    ];
                }
            }
        }

        if ($action == 1) {
            $id = $product->id;
        }

        //For product pricing option
        $this->repository->createProductOption($dbFields, $id, $action);

        return $product;
    }

    /**
     * Handle logic to delete a given logo file.
     *
     * @param $product
     *
     * @return mixed
     */
    public function deleteLogo($product)
    {
        $disk = Storage::disk('s3');
        $logo = $this->logoPath.$product->image_file_name;

        return $disk->delete($logo);
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
        $getProductCategory = $this->repository->getProductCategory($product);

        $productCategories = [];
        foreach ($getProductCategory as $key => $productCategory) {
            $productCategories = array_column($productCategory->ProductCategory->toArray(), 'category_id');
        }

        return $productCategories;
    }

    /**
     * Get Categories.
     *
     * @param $clubId
     *
     * @return mixed
     */
    public function getCategory($clubId, $type = null)
    {
        $category = $this->repository->getCategory($clubId, $type)->toArray();

        $categoryList = [];
        foreach ($category as $key => $val) {
            if (!empty($type)) {
                $categoryList[$val['id']] = $val['title'];
            } else {
                $categoryList[$val['type']][] = $val;
            }
        }

        return $categoryList;
    }

    /**
     * Prepare checkout
     * Prepare checkout for product purchase.
     *
     * @param $consumerCard
     *
     * @return mixed
     * @return \Illuminate\Http\Response
     */
    public function prepareCheckoutForProductPurchase($consumerCard, $currency, $totalPrice)
    {
        $errorFlag = false;
        return ['response' => ['id' => 1], 'errorFlag' => $errorFlag];

        $params = [
            'amount'              => $totalPrice,
            'currency'            => $currency,
            'shopperResultUrl'    => 'app://'.config('app.domain').'/url',
            'notificationUrl'     => config('app.url').'/api/registration_notification',
            'paymentType'         => 'DB',
            'registrations[0].id' => $consumerCard->token,
        ];

        try {
            $response = $this->apiclient->createCheckout($params);
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            $errorFlag = true;
            $response = response()->json(['error' => 'Bad request'], 400);
        }

        return ['response' => $response, 'errorFlag' => $errorFlag];
    }

    public function getProductCollectionPointId($match, $consumer,$data)
    {
        $collectionPointIds = [];
        $i = 0;
        $collectionPointId = NULL;

        if($consumer->club->stadium && $consumer->club->stadium->is_using_allocated_seating == 1) {
        	if($match) {
	            $tickets = TicketTransaction::where('match_id', $match->id)->where('consumer_id',$consumer->id)->get();
	        }
            if(count($tickets)>0){
    	        foreach ($tickets as $ticket) {
    	            foreach ($ticket->bookedTickets as $bookedTicket) {
    	                $collectionPoints = $bookedTicket->stadiumBlockSeat->stadiumBlock->collectionPoints()->select('collection_point_id', \DB::raw('count(collection_point_id) as collection_point_count'))->groupBy('collection_point_id')->get();
    	                foreach ($collectionPoints as $key => $value) {
    	                    $collectionPointIds[$i][$value->collection_point_id] = $value->collection_point_count;
    	                }

    	                $i++;
    	            }
    	        }
            }
            else
            {
               $collectionPoints = StadiumBlock::where('id',$data['stadium_block_id'])->first()->collectionPoints()->select('collection_point_id', \DB::raw('count(collection_point_id) as collection_point_count'))->groupBy('collection_point_id')->get();
                foreach ($collectionPoints as $key => $value) {
                        $collectionPointIds[$i][$value->collection_point_id] = $value->collection_point_count;
                    }
            }

	        if(!empty($collectionPointIds)) {
	            $collectionPointIds = array_unique($collectionPointIds, SORT_REGULAR);
	            arsort($collectionPointIds);
	            if(isset($collectionPointIds[0])) {
	                $collectionPointId = array_keys($collectionPointIds[0])[0];
	            }
	        }
	    } else {
	    	$collectionPointId = CollectionPoint::where('club_id', $consumer->club_id)->pluck('id')->random();
	    }
        return $collectionPointId;
    }

    public function calculateProductTransactionPrice($products)
    {
        $productJson = json_decode($products, true);
        $totalPrice = 0;
        $productIds = [];
        foreach ($productJson as $key => $product) {
            $productData = Product::find($product['product_id']);
            $productVateRate = $productData->vat_rate;
            $perQuantityPrice = $productData->price;
            $productIds[] = $product['product_id'];

            $productJson[$key]['vat_rate'] = $productVateRate;
            $productJson[$key]['per_quantity_price'] = $perQuantityPrice + (( $perQuantityPrice * $productVateRate ) / 100);

            if (isset($product['special_offer_id']) && !empty($product['special_offer_id'])) {
                $productId = $product['product_id'];
                $specialOffer = $this->specialOfferRepository->getSpecialOfferWithProducts($productId, $product['special_offer_id']);
                if ($specialOffer) {
                    $productJson[$key]['per_quantity_actual_price'] = $productJson[$key]['per_quantity_price'];
                    $specialOfferDiscountAmount = $specialOffer->specialOfferProducts->pluck('discount_amount')[0];
                    if ($specialOffer->discount_type == 'fixed_amount') {
                        $productJson[$key]['per_quantity_price'] -= $specialOfferDiscountAmount;
                    } else if ($specialOffer->discount_type == 'percentage') {
                        $productJson[$key]['per_quantity_price'] = ($productJson[$key]['per_quantity_price'])*(1-($specialOfferDiscountAmount/100));
                    }
                }
            }

            $productJson[$key]['total_price'] = $productJson[$key]['per_quantity_price'] * $product['quantity'];

            $optionPrice = 0;
            if(isset($product['product_options'])) {
                foreach ($product['product_options'] as $option) {
                    $productOptionData = ProductOption::find($option['id']);
                    if(isset($productOptionData)) {
                        $optionPrice += $productOptionData->additional_cost + (( $productOptionData->additional_cost * $productVateRate ) / 100);
                    }
                }
            }

            $productJson[$key]['per_quantity_additional_options_cost'] = $optionPrice;

            $productJson[$key]['total_price'] = $productJson[$key]['total_price'] + ($optionPrice * $product['quantity']);
            $totalPrice += $productJson[$key]['total_price'];
        }
        return ['products' => $productJson, 'totalPrice' => $totalPrice, 'productIds' => $productIds];
    }

    /**
     * Handle logic to create a product transactions details.
     *
     * @param $consumerCard
     * @param $consumer
     * @param $currency
     *
     * @return mixed
     */
    public function createProductPurchase($consumer, $data, $totalPrice, $match)
    {
        $transactionData['match_id'] = $match->id;
        $transactionData['club_id'] = $consumer->club_id;
        $transactionData['consumer_id'] = $consumer->id;
        $transactionData['price'] = $totalPrice;
        $transactionData['currency'] = $consumer->club->currency;//$data['txAmountCy'];
        $transactionData['type'] = $data['attributes']['type'];//$data['type']
        $transactionData['transaction_reference_id'] = $data['txId'];
        $transactionData['card_details'] = $data['cardDetails'];
        $transactionData['payment_status'] = 'Unpaid';
        $transactionData['custom_parameters'] = $data['custom_parameters'];
        $product = $this->repository->createProductPurchase($transactionData);
        return $product;
    }

    /**
     * Handle logic to update a receipt number of product.
     *
     * @param $consumer
     * @param $productTransacton
     *
     * @return mixed
     */
    public function updateReceiptNumberOfProduct($consumer, $productTransacton)
    {
        $productTransacton->receipt_number = '#P'.sprintf('%04s', $consumer->club_id).sprintf('%04s', $productTransacton->id);
        $productTransacton->save();
    }

    /**
     * Handle logic to save product options.
     *
     * @param $productTransactonId
     * @param $data
     *
     * @return mixed
     */
    public function savePurchasedProduct($productTransactonId, $data)
    {
        $data['product_transaction_id'] = $productTransactonId;

        foreach (json_decode($data['products']) as $product) {
        	$productId = $product->product_id;
            $data['product_id'] = $product->product_id;
            $data['quantity'] = $product->quantity;
            $data['per_quantity_price'] = $product->per_quantity_price;
            $data['per_quantity_actual_price'] = isset($product->per_quantity_actual_price) ? $product->per_quantity_actual_price : null;
            $data['per_quantity_additional_options_cost'] = $product->per_quantity_additional_options_cost;
            $data['vat_rate'] = $product->vat_rate;
            $data['total_price'] = $product->total_price;
            $data['transaction_timestamp'] = Carbon::now();

            $productArr = (array) $product;
            $data['special_offer_id'] = null;
            $data['special_offer_discount'] = null;
            $data['special_offer_discount_type'] = null;
            if (isset($productArr['special_offer_id']) && !empty($productArr['special_offer_id']))
            {
                $specialOffer = $this->specialOfferRepository->getSpecialOfferWithProducts($product->product_id, $productArr['special_offer_id']);
                if ($specialOffer) {
                    $data['special_offer_id'] = ($specialOffer != null) ? $specialOffer->id : null;
                    $data['special_offer_discount_type'] = ($specialOffer != null) ? $specialOffer->discount_type : null;
                    $data['special_offer_discount'] = ($specialOffer != null) ? $specialOffer->specialOfferProducts->pluck('discount_amount')[0] : null;
                }
            }

            $purchasedProduct = $this->repository->savePurchasedProduct($data);

            foreach ($product->product_options as $productOption) {
                $data['purchased_product_id'] = $purchasedProduct->id;
                $data['product_option_id'] = $productOption->id;
                $this->repository->savePurchasedProductOptions($data);
            }
        }
    }

    /**
     * Product payment
     * Check payment status and response of product.
     *
     * @param $checkoutId
     *
     * @return mixed
     * @return \Illuminate\Http\Response
     */
    public function productPurchasePayment($checkoutId)
    {
        $errorFlag = false;
        return ['response' => ['id' => 1], 'errorFlag' => $errorFlag];

        try {
            $response = $this->apiclient->getPaymentStatus($checkoutId);
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            $errorFlag = true;
            $response = response()->json(['error' => 'Bad request'], 400);
        }

        return ['response' => $response, 'errorFlag' => $errorFlag];
    }

    /**
     * Handle logic to update a product purchase details.
     *
     * @param $paymentResponse
     * @param $productTransactionId
     *
     * @return mixed
     */
    public function updateProductPurchase($data, $match,$consumer,$totalPrice)
    {

        $transactionData['collection_point_id'] = $data['collection_point_id'];
        $transactionData['selected_collection_time'] = $data['selected_collection_time'];
        if($data['selected_collection_time'] == "as_soon_as_possible") {
            $collectionTime = Carbon::now()->format("Y-m-d H:i:s");
        } else if($data['selected_collection_time'] == "half_time") {
            $collectionTime = Carbon::parse($match->kickoff_time)->addMinutes(45)->format("Y-m-d H:i:s");
        } else {
            $collectionTime = Carbon::parse($match->kickoff_time)->addMinutes(105)->format("Y-m-d H:i:s");
        }
        $transactionData['collection_time'] = $collectionTime;
        $transactionData['status'] = $data['transaction_summary']['data']['status'];

         // $transactionData['transaction_reference_id'] = ;
        $transactionData['psp_reference_id'] = $data['transaction_summary']['data']['payload']['pspReferenceId'];

        $transactionData['payment_method'] = isset($data['transaction_summary']['data']['payload']['method']) ? $data['transaction_summary']['data']['payload']['method'] : null;

        $transactionData['status_code'] = isset($data['transaction_summary']['data']['payload']['status_code']) ? $data['transaction_summary']['data']['payload']['status_code'] : null;

        $transactionData['psp'] = $data['transaction_summary']['data']['payload']['psp'];

        $transactionData['psp_account'] = isset($data['transaction_summary']['data']['payload']['psp_account'] ) ? $data['transaction_summary']['data']['payload']['psp_account'] : null;
        $transactionData['transaction_timestamp'] =Carbon::now()->format('Y-m-d H:i:s');

        $productFlag = $this->repository->getProductTransactionData($data['transaction_summary']['data']['payload']['txRefId']);

        if($productFlag)
        {
            $product = $this->repository->updateProductPurchase($transactionData,$productFlag);
        }
        else
        {
            $product = $this->createProductPurchase($consumer,$data,$totalPrice,$match);
        }

        $image =(string) Image::make(QrCode::format('png')->size(300)->generate(json_encode(['url' => 'scan_order', 'transaction_id' => $product->id, 'type' => 'product'])))->encode('data-url');
        $qrcodeImage = uploadQRCodeToS3($image, $this->productTransactionQrcodePath,$product->id);

        return $product;
    }

    /**
     * Handle logic to create or update a product collection point.
     *
     * @param $product
     * @param $data
     * @param $action
     *
     * @return mixed
     */
    protected function createProductCollectionPoint($product,$clubId)
    {
        $dbFields = [];
        $data=$this->collectionPointRepository->getCollectionPoints($clubId);
            foreach ($data as $key => $val) {
                $dbFields[] = [
                    'product_id'  => $product->id,
                    'collection_point_id' => $val->id,
                ];
            }
        $this->repository->createProductCollectionPoint($dbFields);
        return $product;
    }

    /**
     * Handle logic to get product ids of collection points.
     *
     * @return mixed
     */
    public function getProductIdsOfCollectionPoints($clubTimings, $type)
    {
        $tickets = [];
        $user = JWTAuth::user();
        $consumer = Consumer::where('user_id', $user->id)->first();
        $collectionPointIds = [];
        if($type == "food_and_drink") {
            $startDateTime = Carbon::now()->subMinutes($clubTimings->food_and_drink_minutes_open_before_kickoff)->format("Y-m-d H:i:s");
            $endDateTime = Carbon::now()->addMinutes($clubTimings->food_and_drink_minutes_closed_after_fulltime)->format("Y-m-d H:i:s");
        } else if($type == 'merchandise') {
            $startDateTime = Carbon::now()->subMinutes($clubTimings->merchandise_minutes_open_before_kickoff)->format("Y-m-d H:i:s");
            $endDateTime = Carbon::now()->addMinutes($clubTimings->merchandise_minutes_closed_after_fulltime)->format("Y-m-d H:i:s");
        } else if($type == 'loyaltyreward') {
            $startDateTime = Carbon::now()->subMinutes($clubTimings->loyalty_rewards_minutes_open_before_kickoff)->format("Y-m-d H:i:s");
            $endDateTime = Carbon::now()->addMinutes($clubTimings->loyalty_rewards_minutes_closed_after_fulltime)->format("Y-m-d H:i:s");
        }

        $ticketTransactionMatchIds = TicketTransaction::where('club_id', $consumer->club_id)->where('consumer_id', $consumer->id)->pluck('match_id')->toArray();
        $ticketTransactionMatchIds = array_unique($ticketTransactionMatchIds);
        $match = Match::whereIn('id', $ticketTransactionMatchIds)
	        		// ->where(function ($query) {
	          //           $query->where('status', 'scheduled')
	          //               ->orWhere('status', 'in_play');
	          //       })
        			->orderBy('kickoff_time', 'asc')
        			->where('kickoff_time', '>=', $startDateTime)
                	->where('kickoff_time', '<=', $endDateTime)->first();
        if($match)
        {
           $tickets = TicketTransaction::where('match_id', $match->id)->get();
        }
        if($consumer->club->stadium && $consumer->club->stadium->is_using_allocated_seating == 1) {
        	foreach ($tickets as $ticket) {
	            foreach ($ticket->bookedTickets as $bookedTicket) {
	                $collectionPointIds = array_merge($collectionPointIds, $bookedTicket->stadiumBlockSeat->stadiumBlock->collectionPoints->pluck('collection_point_id')->toArray());
	            }
	        }
        } else {
        	$collectionPointIds = CollectionPoint::where('club_id', $consumer->club_id)->pluck('id')->toArray();
        }
        $productIds = ProductCollectionPoint::whereIn('collection_point_id', $collectionPointIds)->pluck('product_id')->toArray();
        return array_unique($productIds);
    }

    /**
     * Handle logic to get product orders.
     *
     * @param $consumerId
     * @param $type
     *
     * @return mixed
     */
    public function getProductOrders($consumerId, $type = "")
    {
        return $this->repository->getProductOrders($consumerId, $type);
    }

    /**
     * Handle logic to get club opening times.
     *
     * @param $clubId
     *
     * @return mixed
     */
    public function getClubOpeningTimeSetting($clubId)
    {
        return $this->repository->getClubOpeningTimeSetting($clubId);
    }

    /**
     * Handle logic to get club matches.
     *
     * @param $clubId
     *
     * @return mixed
     */
    public function getClubMatch($clubId, $type, $clubTimings)
    {
        if($type == "food_and_drink") {

            $startDateTime = Carbon::now()->subMinutes($clubTimings->food_and_drink_minutes_open_before_kickoff)->format("Y-m-d H:i:s");
            $endDateTime = Carbon::now()->addMinutes($clubTimings->food_and_drink_minutes_closed_after_fulltime)->format("Y-m-d H:i:s");

        } else {

            $startDateTime = Carbon::now()->subMinutes($clubTimings->merchandise_minutes_open_before_kickoff)->format("Y-m-d H:i:s");
            $endDateTime = Carbon::now()->addMinutes($clubTimings->merchandise_minutes_closed_after_fulltime)->format("Y-m-d H:i:s");

        }

        return Match::where(function ($query) {
                    $query->where('status', 'scheduled')
                        ->orWhere('status', 'in_play');
                })
                ->where('kickoff_time', '>=', $startDateTime)
                ->where('kickoff_time', '<=', $endDateTime)
            	->where('home_team_id', $clubId)
            	->get();
    }

    /**
     * Handle logic to get status of ticket purchased from APP.
     *
     *
     * @return boolean
     */
    public function getTicketPurchasedFromAppStatus($type, $clubTimings)
    {
    	if($type == "food_and_drink") {
            $startDateTime = Carbon::now()->subMinutes($clubTimings->food_and_drink_minutes_open_before_kickoff)->format("Y-m-d H:i:s");
            $endDateTime = Carbon::now()->addMinutes($clubTimings->food_and_drink_minutes_closed_after_fulltime)->format("Y-m-d H:i:s");
        } else if($type == 'merchandise') {
            $startDateTime = Carbon::now()->subMinutes($clubTimings->merchandise_minutes_open_before_kickoff)->format("Y-m-d H:i:s");
            $endDateTime = Carbon::now()->addMinutes($clubTimings->merchandise_minutes_closed_after_fulltime)->format("Y-m-d H:i:s");
        } else if($type == 'loyaltyreward') {
            $startDateTime = Carbon::now()->subMinutes($clubTimings->loyalty_rewards_minutes_open_before_kickoff)->format("Y-m-d H:i:s");
            $endDateTime = Carbon::now()->addMinutes($clubTimings->loyalty_rewards_minutes_closed_after_fulltime)->format("Y-m-d H:i:s");
        }

        $isTicketPurchasedFromApp = false;
        $user = JWTAuth::user();
        $consumer = Consumer::where('user_id', $user->id)->first();
        $match = Match::where('status', 'scheduled')
        		->where('kickoff_time', '>=', $startDateTime)
                ->where('kickoff_time', '<=', $endDateTime)
        		->where(function($query) use ($consumer) {
        			$query->where('home_team_id', $consumer->club_id)
        			->orWhere('away_team_id', $consumer->club_id);
        		})
        		->orderBy('kickoff_time', 'asc')
        		->first();
        if(isset($match)) {
            $ticketTransaction = TicketTransaction::where('club_id', $consumer->club_id)->where('consumer_id', $consumer->id)->where('match_id', $match->id)->first();
            if(isset($ticketTransaction)) {
                $isTicketPurchasedFromApp = true;
            }
        }

        return $isTicketPurchasedFromApp;
    }

    /**
     * Handle logic to get special offer products.
     *
     * @param $consumer
     * @param $specialOffers
     *
     * @return mix
     */
    public function getSpecialOfferProducts($consumer, $specialOffers)
    {
        $isSpecialOfferAccessible = false;
        $productsData = [];
        $membershipPackages = null;
        $consumerPackageId = null;
        $membershipPackagesName = null;
        $consumerPackage = $consumer->getActiveMembershipPackage();

        $specialOfferMembershipPackages = $specialOffers->specialOfferMembershipPackageAvailability->pluck('membership_package_id')->toArray();

        if(isset($consumerPackage)) {
            $consumerPackageId = $consumerPackage->membership_package_id;
        }

        if( ($consumerPackageId && in_array($consumerPackageId, $specialOfferMembershipPackages)) || in_array(config('fanslive.ALL_FANS_MEMBERSHIP_PACKAGE_ID'), $specialOfferMembershipPackages)) {
            $productIds = $specialOffers->specialOfferProducts->pluck('product_id');
            $productsData = Product::whereIn('id', $productIds)
                                    ->when($specialOffers->is_restricted_to_over_age==1, function($q) {
                                        $q->where('is_restricted_to_over_age', 1);
                                    })
                                    ->get();
            $isSpecialOfferAccessible = true;
        }

        if(!$isSpecialOfferAccessible) {
            $membershipPackagesName = MembershipPackage::where('status', 'Published')->whereIn('id', $specialOfferMembershipPackages)->pluck('title')->toArray();

            if(count($specialOfferMembershipPackages) == 1) {
                $membershipPackages = MembershipPackage::where('status', 'Published')->whereIn('id', $specialOfferMembershipPackages)->first();
            }
        }

        return ['isSpecialOfferAccessible' => $isSpecialOfferAccessible, 'products' => $productsData, 'membershipPackages' => $membershipPackages, 'membershipPackagesName' => $membershipPackagesName];
    }

    /**
     * Handle logic to get category products.
     *
     * @param $categoryId
     * @param $consumerAge
     *
     * @return mix
     */
    public function getCategoryProducts($categoryId, $clubTimings, $consumerAge = "")
    {
        $productCategoryData = Category::with('categoryProducts')->where('id', $categoryId);

        if($consumerAge < config('fanslive.IS_RESTRICTED_TO_OVER_AGE')) {
            $productCategoryData = $productCategoryData->where('is_restricted_to_over_age', 0);
        }

        $productCategoryData = $productCategoryData->first();

        $isSpecialOfferAccessible = true;

        $productsData = [];

        if(isset($productCategoryData)) {
            $productsData = $productCategoryData->categoryProducts->whereIn('id', $this->getProductIdsOfCollectionPoints($clubTimings, $productCategoryData->type));
        }

        return ['isSpecialOfferAccessible' => $isSpecialOfferAccessible, 'products' => $productsData];
    }

    /**
     * Handle logic to get product for age restricted consumer.
     *
     * @param $consumer object
     * @param $productIds=[]
     *
     * @return mixed
     */
    public function getProductsForAgeRestrictedConsumer($consumer, $productIds = [])
    {
        $isAgeRestricted = FALSE;
        if ($consumer && (Carbon::parse($consumer->date_of_birth)->age < config('fanslive.IS_RESTRICTED_TO_OVER_AGE'))) {
            $isAgeRestricted = TRUE;
        }
        return $this->repository->getProductsForAgeRestrictedConsumer($isAgeRestricted, $productIds);
    }

    /**
     * unset class instance or public property.
     */
    public function __destruct()
    {
        unset($this->repository);
        unset($this->logoPath);
    }
    /**
    * validate  product Payment
    */
    public function validateProductPayment($data, $consumer)
    {
    	$clubTimings = $this->clubAppSettingService->getClubOpeningTimeSetting($consumer->club_id);
        $matches = $this->getClubMatch($consumer->club_id, $data['type'], $clubTimings);
        if ($matches->count() == 0)
        {
            return response()->json([
                'message' => 'Our shop is currently not taking orders. We will be open again soon.'
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
        $products = $this->getProductsForAgeRestrictedConsumer($consumer, $productIds);
        if (count($products) != count($productIds)) {
            return response()->json([
                'message' => 'Something went wrong! Count of products is not matching.'
            ], 400);
        }
        $productData = $this->calculateProductTransactionPrice($data['products']);
        \Log::info($productData);
        if ($productData['totalPrice'] != $data['final_amount']) {
            return response()->json([
                'message' => 'Something went wrong! Cart amount is not matching.'
            ], 400);
        }

        return response()->json([
        	'message' => 'Cart is validated successfully.',
        ]);
    }
    /**
    * authorised  product Payment
    */
    public function authoriseProductPayment($data, $consumer)
    {
    	// return [
     //            'message' => 'Our shop is currently not taking orders.<br> We will be open again soon.',
     //            'status' =>'error',
     //            'code' => 10400,
     //        ];
        $clubTimings = $this->clubAppSettingService->getClubOpeningTimeSetting($consumer->club_id);
        $matches = $this->getClubMatch($consumer->club_id, $data['type'], $clubTimings);
        if ($matches->count() == 0)
        {
        	\Log::info("authorize issue 1");
            return [
                'message' => 'Our shop is currently not taking orders.<br> We will be open again soon.',
                'status' =>'error',
                'code' => 10400,
            ];
        }
        $match = $this->matchService->getConsumerMatch($consumer, $data['type'], $clubTimings);
        if (!isset($match)) {
        	\Log::info("authorize issue 2");
            return [
                'message' => 'No match found.',
                'status' =>'error',
                'code' => 400,
            ];
        }
        $productIds = explode(",", $data['product_id']);
        $products = $this->getProductsForAgeRestrictedConsumer($consumer, $productIds);

        if (count($products) != count($productIds)) {
        	\Log::info("authorize issue 3");
            return [
                'message' => 'No product found.',
                'status' =>'error',
                'code'=>400,
            ];
        }
        return [
                'status' => 'success',
                'code' => 200,
            ];
    }

    public function getEarnedLoyaltyPoints($consumer, $productTransaction)
    {
    	$earnedLoyaltyPoints = 0;
    	$rewardPercentage = 0;
    	$clubLoyaltyPointData = ClubLoyaltyPointSetting::where('club_id', $consumer->club_id)->first();
		if ($productTransaction->type == 'food_and_drink') {
			$rewardPercentage = $clubLoyaltyPointData->food_and_drink_reward_percentage;
		} else {
			$rewardPercentage = $clubLoyaltyPointData->merchandise_reward_percentage;
		}
    	$purchasedProducts = PurchasedProduct::with('product')->where('product_transaction_id', $productTransaction->id)->get();
    	foreach($purchasedProducts as $purchasedProduct) {
    		if($purchasedProduct->product->rewards_percentage_override !== null && $purchasedProduct->product->rewards_percentage_override !== '') {
    			$earnedLoyaltyPoints += $purchasedProduct->total_price * $purchasedProduct->product->rewards_percentage_override;
    		} else {
    			$earnedLoyaltyPoints += $purchasedProduct->total_price * $rewardPercentage;
    		}
    	}

		return $earnedLoyaltyPoints;
    }
    /**
     * Handle logic upload QR code of booked events.
     *
     * @param $eventTransactonId
     * @param $data
     *
     * @return mixed
     */
    public function uploadQRcode()
    {
        $purchasedProduct= $this->repository->getAllPurchaseProduct();
        foreach($purchasedProduct as $productTransaction)
        {
           $image =(string) Image::make(QrCode::format('png')->size(300)->generate(json_encode(['url' => 'scan_order', 'transaction_id' => $productTransaction->id, 'type' => 'product'])))->encode('data-url');
            $qrcodeImage = uploadQRCodeToS3($image, $this->productTransactionQrcodePath,$productTransaction->id);
        }
    }
}
