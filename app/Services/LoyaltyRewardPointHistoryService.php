<?php

namespace App\Services;

use App\Repositories\LoyaltyRewardPointHistoryRepository;

class LoyaltyRewardPointHistoryService
{
	/**
	 * The loyalty reward repository instance.
	 *
	 * @var repository
	 */
	protected $repository;
	/**
	 * Create a new service instance.
	 *
	 * @param LoyaltyRewardPointHistoryRepository $repository
	 */
	public function __construct(LoyaltyRewardPointHistoryRepository $repository)
	{
		$this->repository = $repository;
	}

	/**
	 * Handle logic to create a Loyalty Reward Point History.
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function createLoyaltyRewardPointHistory($consumer, $transactionId, $point, $type)
	{
		$LoyaltyRewardPointHistory = $this->repository->create($consumer, $transactionId, $point, $type);

		return $LoyaltyRewardPointHistory;
	}

	/**
	 * Get consumer loyalty reward point history detail.
	 *
	 * @param $userId
	 *
	 * @return mixed
	 */
	public function getConsumerLoyaltyRewardPointBalance($userId)
	{
		$consumer = $this->repository->getConsumerLoyaltyRewardPointBalance($userId);

		return $consumer;
	}

}
