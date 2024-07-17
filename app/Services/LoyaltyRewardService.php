<?php

namespace App\Services;

use App\Models\Consumer;
use App\Models\LoyaltyReward;
use App\Models\LoyaltyRewardCollectionPoint;
use App\Models\LoyaltyRewardOption;
use App\Models\Match;
use App\Models\Product;
use App\Models\ProductCollectionPoint;
use App\Models\CollectionPoint;
use App\Models\ProductOption;
use App\Models\PurchasedLoyaltyRewardProduct;
use App\Models\TicketTransaction;
use App\Repositories\CategoryRepository;
use App\Repositories\LoyaltyRewardRepository;
use App\Repositories\StadiumBlockRepository;
use App\Repositories\CollectionPointRepository;
use Carbon\Carbon;
use File;
use Storage;
use JWTAuth;
use DB;
use Image;
use QrCode;
/**
 * Loyalty Reward class to handle operator interactions.
 */
class LoyaltyRewardService
{
	/**
	 * The loyalty reward repository instance.
	 *
	 * @var repository
	 */
	protected $repository;

	/**
	 * The stadium block repository instance.
	 *
	 * @var repository
	 */
	protected $stadiumBlockRepository;

	/**
	 * The category repository instance.
	 *
	 * @var repository
	 */
	protected $categoryRepository;

	/**
     * The collection point repository instance.
     *
     * @var repository
     */
    protected $collectionPointRepository;

	/**
	 * The loyalty reward image path.
	 *
	 * @var imagePath
	 */
	protected $imagePath;

	/**
	 * Create a new service instance.
	 *
	 * @param LoyaltyRewardRepository $repository
	 */

	/**
     * Create a QRcode path  variable.
     *
     * @return void
     */
    protected $loyaltyRewardTransactionQrcodePath;

	public function __construct(LoyaltyRewardRepository $repository, StadiumBlockRepository $stadiumBlockRepository, CategoryRepository $categoryRepository, CollectionPointRepository $collectionPointRepository)
	{
		$this->repository = $repository;
		$this->categoryRepository = $categoryRepository;
		$this->stadiumBlockRepository = $stadiumBlockRepository;
		$this->collectionPointRepository = $collectionPointRepository;
		$this->imagePath = config('fanslive.IMAGEPATH.loyalty_reward_image');
		$this->loyaltyRewardTransactionQrcodePath = config('fanslive.IMAGEPATH.loyalty_reward_transaction_qrcode');
	}

	/**
	 * Get loyalty reward data.
	 *
	 * @param $clubId
	 * @param $data
	 *
	 * @return mixed
	 */
	public function getData($clubId, $data)
	{
		$loyaltyRewards = $this->repository->getData($clubId, $data);

		return $loyaltyRewards;
	}

	/**
	 * Handle logic to get only loyalty rewards collection points.
	 *
	 * @param $loyaltyRewards
	 *
	 * @return mixed
	 */
	public function loyaltyRewardsCollectionPoints($loyaltyRewards)
	{
		$collectionPoints = $loyaltyRewards->loyaltyRewardsCollectionPoints->toArray();
		$collectionPointsArr = array_column($collectionPoints, 'collection_point_id');
		return $collectionPointsArr;
	}

	/**
	 * Handle logic to create a loyalty reward.
	 *
	 * @param $clubId
	 * @param $user
	 * @param $data
	 *
	 * @return mixed
	 */
	public function create($clubId, $user, $data)
	{
		if (isset($data['image'])) {
			$image = uploadImageToS3($data['image'], $this->imagePath);
			$data['image'] = $image['url'];
			$data['image_file_name'] = $image['file_name'];
		} else {
			$data['image'] = null;
			$data['image_file_name'] = null;
		}
		$loyaltyReward = $this->repository->create($clubId, $user, $data);
		if (!empty($loyaltyReward)) {

			// Insert loyalty reward collection point
			$this->createLoyaltyRewardCollectionPoint($loyaltyReward,$clubId);

			// Insert loyalty reward option
			$this->createLoyaltyRewardOption($loyaltyReward, $data);
		}

		return $loyaltyReward;
	}

	/**
	 * Handle logic to update a given loyalty reward.
	 *
	 * @param $user
	 * @param $loyaltyReward
	 * @param $data
	 *
	 * @return mixed
	 */
	public function update($user, $loyaltyReward, $data)
	{
		$disk = Storage::disk('s3');
		if (isset($data['image'])) {
			$existingImage = $this->imagePath . $loyaltyReward->image_file_name;
			$disk->delete($existingImage);
			$image = uploadImageToS3($data['image'], $this->imagePath);
			$data['image'] = $image['url'];
			$data['image_file_name'] = $image['file_name'];
		} else {
			$data['image'] = $loyaltyReward->image;
			$data['image_file_name'] = $loyaltyReward->image_file_name;
		}

		$loyaltyRewardToUpdate = $this->repository->update($user, $loyaltyReward, $data);

		if (!empty($loyaltyRewardToUpdate)) {
			// Insert loyalty reward collection point
			//$this->createLoyaltyRewardCollectionPoint($loyaltyRewardToUpdate, $data, 1);

			// Insert loyalty reward option
			$this->createLoyaltyRewardOption($loyaltyRewardToUpdate, $data, 1);
		}

		return $loyaltyRewardToUpdate;
	}

	/**
	 * Handle logic to create or update a product category.
	 *
	 * @param $loyaltyReward
	 * @param $data
	 * @param $action
	 *
	 * @return mixed
	 */
	protected function createLoyaltyRewardCollectionPoint($loyaltyReward,$clubId)
	{
		$dbFields = [];
		$data=$this->collectionPointRepository->getCollectionPoints($clubId);
            foreach ($data as $key => $val) {
                $dbFields[] = [
                    'loyalty_reward_id' => $loyaltyReward->id,
                    'collection_point_id' => $val->id,
                ];
            }
        $this->repository->createLoyaltyRewardCollectionPoint($dbFields);
        return $loyaltyReward;
	}

	/**
	 * Handle logic to create or update a product pricing options.
	 *
	 * @param $loyaltyReward
	 * @param $data
	 * @param $action
	 *
	 * @return mixed
	 */
	protected function createLoyaltyRewardOption($loyaltyReward, $data, $action = 0)
	{
		$dbFields = [];
		$id = null;

		if (!empty($data['additional_cost']) && !empty($data['name'])) {
			if (count($data['additional_cost']) == count($data['name'])) {
				foreach ($data['additional_cost'] as $key => $val) {
					$dbFields[] = [
						'loyalty_reward_id' => $loyaltyReward->id,
						'additional_point' => $data['additional_cost'][$key],
						'name' => $data['name'][$key],
					];
				}
			}
		}

		if ($action == 1) {
			$id = $loyaltyReward->id;
		}

		//For loyalty reward option
		$this->repository->createLoyaltyRewardOption($dbFields, $id, $action);

		return $loyaltyReward;
	}

	/**
	 * Handle logic to delete a given logo file.
	 *
	 * @param $loyaltyReward
	 *
	 * @return mixed
	 */
	public function deleteLogo($loyaltyReward)
	{
		$disk = Storage::disk('s3');
		$logo = $this->imagePath . $loyaltyReward->image_file_name;

		return $disk->delete($logo);
	}

	/**
	 * unset class instance or public property.
	 */
	public function __destruct()
	{
		unset($this->repository);
		unset($this->imagePath);
	}

	public function calculateLoyaltyReward($loyaltyRewards)
	{
		$loyaltyRewardJson = json_decode($loyaltyRewards);
		$totalPoints = 0;
		$loyaltyRewardIds = [];
		foreach ($loyaltyRewardJson as $key => $loyaltyReward) {
			$loyaltyRewardData = LoyaltyReward::find($loyaltyReward->loyalty_reward_product_id);

			$perQuantityPoints = $loyaltyRewardData->price_in_points;
			$loyaltyRewardIds[] = $loyaltyReward->loyalty_reward_product_id;

			$loyaltyRewardJson[$key]->per_quantity_points = $perQuantityPoints;
			$loyaltyRewardJson[$key]->total_points = $loyaltyRewardJson[$key]->per_quantity_points * $loyaltyReward->quantity;

			$optionPoint = 0;
			if (isset($loyaltyReward->loyalty_reward_options)) {
				foreach ($loyaltyReward->loyalty_reward_options as $option) {
					$loyaltyRewardOptionData = LoyaltyRewardOption::find($option->id);

					if (isset($loyaltyRewardOptionData)) {
						$optionPoint += $loyaltyRewardOptionData->additional_point;
					}
				}
			}

			$loyaltyRewardJson[$key]->per_quantity_additional_options_point = $optionPoint;

			$loyaltyRewardJson[$key]->total_points = $loyaltyRewardJson[$key]->total_points + ($optionPoint * $loyaltyReward->quantity);
			$totalPoints += $loyaltyRewardJson[$key]->total_points;
		}
		return ['loyaltyRewards' => $loyaltyRewardJson, 'totalPoints' => $totalPoints, 'loyaltyRewardIds' => $loyaltyRewardIds];
	}

	/**
	 * Handle logic to create a loyalty reward transactions details.
	 *
	 * @param $consumer
	 * @param $data
	 * @param $totalPoints
	 * @param $match
	 *
	 * @return mixed
	 */
	public function createLoyaltyRewardPurchase($consumer, $data, $totalPoints, $match)
	{
		$transactionData['match_id'] = $match->id;
		$transactionData['club_id'] = $consumer->club_id;
		$transactionData['consumer_id'] = $consumer->id;
		$transactionData['receipt_number'] = $consumer->receipt_number;
		$transactionData['points'] = $totalPoints;
		$transactionData['selected_collection_time'] = $data['selected_collection_time'];
		if ($data['selected_collection_time'] == "as_soon_as_possible") {
			$collectionTime = Carbon::now()->format("Y-m-d H:i:s");
		} else if ($data['selected_collection_time'] == "half_time") {
			$collectionTime = Carbon::parse($match->kickoff_time)->addMinutes(45)->format("Y-m-d H:i:s");
		} else {
			$collectionTime = Carbon::parse($match->kickoff_time)->addMinutes(105)->format("Y-m-d H:i:s");
		}
		$transactionData['collection_time'] = $collectionTime;
		$transactionData['collection_point_id'] = $data['collection_point_id'];
		$transactionData['transaction_timestamp'] = Carbon::now()->format("Y-m-d H:i:s");

		$loyaltyReward = $this->repository->createLoyaltyRewardPurchase($transactionData);
		$image =(string) Image::make(QrCode::format('png')->size(300)->generate(json_encode(['url' => 'scan_order', 'transaction_id' => $loyaltyReward->id, 'type' => 'loyalty_reward'])))->encode('data-url');
        $qrcodeImage = uploadQRCodeToS3($image, $this->loyaltyRewardTransactionQrcodePath,$loyaltyReward->id);

		return $loyaltyReward;
	}

	/**
	 * Handle logic to save product options.
	 *
	 * @param $loyaltyRewardTransactionId
	 * @param $data
	 *
	 * @return mixed
	 */
	public function savePurchasedLoyaltyReward($loyaltyRewardTransactionId, $data)
	{
		$data['loyalty_reward_transaction_id'] = $loyaltyRewardTransactionId;

		foreach (json_decode($data['loyaltyRewards']) as $loyaltyReward) {
			$data['loyalty_reward_id'] = $loyaltyReward->loyalty_reward_product_id;
			$data['quantity'] = $loyaltyReward->quantity;
			$data['per_quantity_points'] = $loyaltyReward->per_quantity_points;
			$data['per_quantity_additional_options_point'] = $loyaltyReward->per_quantity_additional_options_point;
			$data['total_points'] = $loyaltyReward->total_points;

			$purchasedLoyaltyRewardProduct = $this->repository->savePurchasedLoyaltyRewardProduct($data);
			if (isset($loyaltyReward->loyalty_reward_options)) {
				foreach ($loyaltyReward->loyalty_reward_options as $loyaltyRewardOption) {
					$data['purchased_loyalty_reward_product_id'] = $purchasedLoyaltyRewardProduct->id;
					$data['loyalty_reward_option_id'] = $loyaltyRewardOption->id;
					$this->repository->savePurchasedLoyaltyRewardOptions($data);
				}
			}
		}
	}

	/**
	 * Handle logic to update a receipt number of product.
	 *
	 * @param $consumer
	 * @param $loyaltyRewardTransaction
	 *
	 * @return mixed
	 */
	public function updateReceiptNumberOfLoyaltyReward($consumer, $loyaltyRewardTransaction)
	{
		$loyaltyRewardTransaction->receipt_number = '#L' . sprintf('%04s', $consumer->club_id) . sprintf('%04s', $loyaltyRewardTransaction->id);
		$loyaltyRewardTransaction->save();
	}

	/**
	 * Handle logic to get loyalty reward ids of collection points.
	 *
	 * @return mixed
	 */
	public function getLoyaltyRewardIdsOfCollectionPoints()
	{
		$tickets = [];
		$user = JWTAuth::user();
		$collectionPointIds = [];
		$consumer = Consumer::where('user_id', $user->id)->first();
		$ticketTransactionMatchIds = TicketTransaction::where('club_id', $consumer->club_id)->where('consumer_id', $consumer->id)->pluck('match_id')->toArray();
		$match = Match::whereIn('id', $ticketTransactionMatchIds)->where('status', 'scheduled')->orderBy('kickoff_time', 'asc')->where(DB::raw('CONVERT(kickoff_time, date)'), '>=', Carbon::today())->first();
		if ($match) {
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
		$loyaltyRewardIds = LoyaltyRewardCollectionPoint::whereIn('collection_point_id', $collectionPointIds)->pluck('loyalty_reward_id');
		return $loyaltyRewardIds;
	}

	/**
	 * Handle logic to get loyalty reward transactions.
	 *
	 * @param $consumerId
	 *
	 * @return mixed
	 */
	public function getLoyaltyRewardTransactions($consumerId)
	{
		return $this->repository->getLoyaltyRewardTransactions($consumerId);
	}

	/**
	 * Handle logic to get club loyalty reward matches.
	 *
	 * @param $clubId
	 * @param $clubTimings
	 *
	 * @return mixed
	 */
	public function getClubLoyaltyPointRewardMatch($clubId, $clubTimings)
	{

		$startDateTime = Carbon::now()->subMinutes($clubTimings->loyalty_rewards_minutes_open_before_kickoff)->format("Y-m-d H:i:s");
		$endDateTime = Carbon::now()->addMinutes($clubTimings->loyalty_rewards_minutes_closed_after_fulltime)->format("Y-m-d H:i:s");

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
	 * Handle logic to get loyalty rewards.
	 *
	 * @param $products
	 *
	 * @return mixed
	 */
	public function getLoyaltyRewardBasedOnSeat($blockId, $stadiumBlock)
	{
		$collectionPointIds = $stadiumBlock->collectionPoints->pluck('collection_point_id');

		$loyaltyRewardIds = $this->repository->getLoyaltyRewardBasedOnLoyaltyRewardCollectionPointIds($collectionPointIds);

		return $this->repository->getLoyaltyRewardBasedLoyaltyRewardIds($loyaltyRewardIds);

	}
	/**
	 * Handle logic to get products based on product ids.
	 *
	 * @param $productIds
	 *
	 * @return mixed
	 */
	public function getLoyaltyRewardBasedLoyaltyRewardIds($productIds)
	{
		return Product::whereIn('id', $productIds)->get();
	}

    /**
     * Handle logic to get loyalty reward for age restricted consumer.
     *
     * @param $clubId
     * @param $consumer object
     * @param $loyaltyRewardProductIds=[]
     *
     * @return mixed
     */
    public function getLoyaltyRewardForAgeRestrictedConsumer($clubId, $consumer, $loyaltyRewardProductIds = [])
    {
    	$isAgeRestricted = FALSE;
    	if ($consumer && (Carbon::parse($consumer->date_of_birth)->age < config('fanslive.IS_RESTRICTED_TO_OVER_AGE'))) {
    		$isAgeRestricted = TRUE;
    	}
        return $this->repository->getLoyaltyRewardForAgeRestrictedConsumer($clubId, $isAgeRestricted, $loyaltyRewardProductIds);
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
		$loyaltyReward = $this->repository->getAllLoyaltyReward();
		foreach($loyaltyReward as $loyaltyRewardVal)
		{
			$image =(string) Image::make(QrCode::format('png')->size(300)->generate(json_encode(['url' => 'scan_order', 'transaction_id' => $loyaltyRewardVal->id, 'type' => 'loyalty_reward'])))->encode('data-url');
        	$qrcodeImage = uploadQRCodeToS3($image, $this->loyaltyRewardTransactionQrcodePath,$loyaltyRewardVal->id);
		}
	}
}
