<?php

namespace App\Services;

use App\Repositories\CollectionPointRepository;
use App\Repositories\ProductRepository;
use App\Repositories\LoyaltyRewardRepository;
use App\Http\Resources\ProductTransaction\ProductTransaction as ProductTransactionResource;
use App\Http\Resources\LoyaltyRewardTransaction\LoyaltyRewardTransaction as LoyaltyRewardTransactionResource;

/**
 * Collection Point class to handle operator interactions.
 */
class CollectionPointService
{
	/**
	 * The collection point repository instance.
	 *
	 * @var repository
	 */
	protected $repository;

	/**
	 * The product repository instance.
	 *
	 * @var repository
	 */
	protected $productRepository;

	/**
	 * The loyalty reward repository instance.
	 *
	 * @var repository
	 */
	protected $loyaltyRewardRepository;

	/**
	 * Create a new service instance.
	 *
	 * @param CollectionPointRepository $repository
	 */
	public function __construct(CollectionPointRepository $repository, ProductRepository $productRepository, LoyaltyRewardRepository $loyaltyRewardRepository)
	{
		$this->repository 				= $repository;
		$this->productRepository 		= $productRepository;
		$this->loyaltyRewardRepository 	= $loyaltyRewardRepository;
	}

	/**
	 * Handle logic to create a collection points.
	 *
	 * @param $clubId
	 * @param $user
	 * @param $data
	 *
	 * @return mixed
	 */
	public function create($clubId, $user, $data)
	{
		$collectionPoint = $this->repository->create($clubId, $user, $data);
		if (!empty($collectionPoint)) {

			// Insert collection point stadium block
			$this->createCollectionPointStadiumBlock($collectionPoint, $data);
		}

		return $collectionPoint;
	}

	/**
	 * Handle logic to create or update a collection point stadium block.
	 *
	 * @param $collectionPoint
	 * @param $data
	 * @param $action
	 *
	 * @return mixed
	 */
	protected function createCollectionPointStadiumBlock($collectionPoint, $data, $action = 0)
	{
		$dbFields = [];
		$id = null;
		if (!empty($data['blocks'])) {
			foreach ($data['blocks'] as $key => $val) {
				$dbFields[] = [
					'stadium_block_id' => $val,
					'collection_point_id' => $collectionPoint->id,
				];
			}
		}

		if ($action == 1) {
			$id = $collectionPoint->id;
		}

		//For collection point stadium block
		$this->repository->createCollectionPointStadiumBlock($dbFields, $id, $action);

		return $collectionPoint;
	}

	/**
	 * Get travel offer data.
	 *
	 * @param $clubId
	 * @param $data
	 *
	 * @return mixed
	 */
	public function getData($clubId, $data)
	{
		$collectionPoint = $this->repository->getData($clubId, $data);

		return $collectionPoint;
	}

	/**
	 * Handle logic to prepare Blocks Data with Stadium entrances.
	 *
	 * @param $stadiumEntrance
	 * @param $stadiumBlocks
	 *
	 * @return mixed
	 */
	public function prepareBlocksData($stadiumEntrance, $stadiumBlocks)
	{
		foreach ($stadiumEntrance as $key => $stadiumEntranceEach) {
			$blocks = array_column($stadiumEntranceEach->stadiumEntranceBlocks->toArray(), 'stadium_block_id');

			array_walk($blocks, function (&$item) use ($stadiumBlocks) {
				if (isset($stadiumBlocks[$item])) {
					$item = $stadiumBlocks[$item];
				} else {
					$item = null;
				}
			});

			$stadiumEntranceEach->blocks = implode(', ', array_filter($blocks));
			$stadiumEntrance[$key] = $stadiumEntranceEach;
		}

		return $stadiumEntrance;
	}

	/**
	 * Handle logic to update a given news.
	 *
	 * @param $user
	 * @param $news
	 * @param $data
	 *
	 * @return mixed
	 */
	public function update($user, $collectionpoint, $data)
	{
		$collectionPointToUpdate = $this->repository->update($user, $collectionpoint, $data);
		if (!empty($collectionpoint)) {

			// Insert collection point stadium block
			$this->createCollectionPointStadiumBlock($collectionpoint, $data, $action = 1); //$collectionPoint, $data, $action = 0
		}

		return $collectionPointToUpdate;
	}

	/**
	 * Handle logic to update a given order status.
	 *
	 * @param $collectionPoint
	 * @param $status
	 *
	 * @return mixed
	 */
	public function updateOrderStatus($collectionPoint, $status, $staffId = null)
	{
		$collectionPointToUpdateOrderStatus = $this->repository->updateOrderStatus($collectionPoint, $status, $staffId);
		return $collectionPointToUpdateOrderStatus;
	}

	/**
	 * Handle logic to create a new entry for product and loyalty reward transaction.
	 *
	 * @param $transactionId
	 * @param $type
	 *
	 * @return mixed
	 */
	public function createProductAndLoyaltyRewardTransaction($transactionId, $type)
	{
		$productAndLoyaltyRewardTransaction = $this->repository->createProductAndLoyaltyRewardTransaction($transactionId, $type);
		return $productAndLoyaltyRewardTransaction;
	}

	/**
	 * Handle logic to get product and loyalty transaction collection point
	 *
	 * @param $transactionId
	 * @param $type
	 *
	 * @return mixed
	 */
	public function getProductAndLoyaltyRewardTransactionsCollectionPointWise($clubId, $collectionPointId)
	{
		$productTransaction = $this->productRepository->getProductTransactionsForCollectionPoint($clubId, $collectionPointId);
		$loyaltyReward = $this->loyaltyRewardRepository->getLoyaltyTransactionsForCollectionPoint($clubId, $collectionPointId);
		$collection = collect([]);

		$dataCollection = $collection->merge($productTransaction)->merge($loyaltyReward);
		$dataCollectionSortData = $dataCollection->groupBy('selected_collection_time')
			->map
			->sortBy('transaction_timestamp');

		$halfTimeData = $fullTimeData = $asSoonAsPossibleData = [];
		if (isset($dataCollectionSortData['half_time'])) {
			$halfTimeSortCollection = $dataCollectionSortData['half_time']->sortBy('transaction_timestamp');
			foreach ($halfTimeSortCollection as $halfTime) {
				if ($halfTime->transaction_type == 'product') {
					$halfTimeData[] = new ProductTransactionResource($halfTime);
				} else if ($halfTime->transaction_type == 'loyalty_reward') {
					$halfTimeData[] = new LoyaltyRewardTransactionResource($halfTime);
				}
			}
		}
		if (isset($dataCollectionSortData['full_time'])) {
			$fullTimeSortCollection = $dataCollectionSortData['full_time']->sortBy('transaction_timestamp');
			foreach ($fullTimeSortCollection as $fullTime) {
				if ($fullTime->transaction_type == 'product') {
					$fullTimeData[] = new ProductTransactionResource($fullTime);
				} else if ($fullTime->transaction_type == 'loyalty_reward') {
					$fullTimeData[] = new LoyaltyRewardTransactionResource($fullTime);
				}
			}
		}
		if (isset($dataCollectionSortData['as_soon_as_possible'])) {
			$asSoonAsPossibleTimeSortCollection = $dataCollectionSortData['as_soon_as_possible']->sortBy('transaction_timestamp');
			foreach ($asSoonAsPossibleTimeSortCollection as $asSoonAsPossible) {
				if ($asSoonAsPossible->transaction_type == 'product') {
					$asSoonAsPossibleData[] = new ProductTransactionResource($asSoonAsPossible);
				} else if ($asSoonAsPossible->transaction_type == 'loyalty_reward') {
					$asSoonAsPossibleData[] = new LoyaltyRewardTransactionResource($asSoonAsPossible);
				}
			}
		}

		$halfTimeDataSource = [
			'half_time' => $halfTimeData
		];
		$fullTimeDataSource = [
			'full_time' => $fullTimeData
		];
		$asSoonAsPossibleDataSource = [
			'as_soon_as_possible' => $asSoonAsPossibleData
		];

		$dataCollectionArray = $collection->merge($halfTimeDataSource)->merge($fullTimeDataSource)->merge($asSoonAsPossibleDataSource);

		return $dataCollectionArray;
	}

	/**
	 * Get remaining time.
	 *
	 * @param $kickOffTime
	 * @param $timezone
	 * @param $minutes
	 *
	 * @return mixed
	 */
	public function getRemainingTime($kickOffTime, $timezone, $minutes)
	{
		$halfTime = $kickOffTime->addMinutes($minutes)->format('Y-m-d H:i:s');
		$date = convertDateTimezone($halfTime, 'UTC', $timezone);
		return getDateDiff($date, $timezone, 1);
	}

	/**
     * Get Collection point
     *
     * @param $clubId
     *
     * @return mixed
     */
    public function getCollectionPoints($clubId)
    {
        $collectionPoints = $this->repository->getCollectionPoints($clubId)->pluck('title','id')->toArray();
        return $collectionPoints;
    }
}
