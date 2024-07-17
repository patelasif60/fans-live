<?php


namespace App\Repositories;

use App\Models\LoyaltyRewardPointHistory;

class LoyaltyRewardPointHistoryRepository
{
	/**
	 * Get consumer loyalty reward balance.
	 *
	 * @param $consumer
	 * @param $transactionId
	 * @param $point
	 * @param $type
	 *
	 * @return mixed
	 */
	public function create($consumer, $transactionId, $point, $type)
	{
		$loyaltyRewardPointHistory = LoyaltyRewardPointHistory::create([
			'consumer_id' => $consumer->id,
			'transaction_id' => $transactionId,
			'transaction_type' => $type,
			'points' => $point,
		]);

		return $loyaltyRewardPointHistory;
	}

	/**
	 * Get consumer loyalty reward balance.
	 *
	 * @param $userId
	 *
	 * @return mixed
	 */
	public function getConsumerLoyaltyRewardPointBalance($userId)
	{
		$loyaltyRewardTotalCredit = LoyaltyRewardPointHistory::where('consumer_id', $userId)->where('transaction_type', '!=','loyalty_reward')->sum('points');
		$loyaltyRewardTotalDebit = LoyaltyRewardPointHistory::where('consumer_id', $userId)->where('transaction_type','loyalty_reward')->sum('points');
		$consumerLoyaltyRewardPointBalance = $loyaltyRewardTotalCredit - $loyaltyRewardTotalDebit;
		return $consumerLoyaltyRewardPointBalance;
	}
}
