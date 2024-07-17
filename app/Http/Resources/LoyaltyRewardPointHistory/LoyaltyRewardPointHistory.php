<?php


namespace App\Http\Resources\LoyaltyRewardPointHistory;


use App\Models\ConsumerMembershipPackage;
use App\Models\EventTransaction;
use App\Models\HospitalitySuiteTransaction;
use App\Models\LoyaltyRewardTransaction;
use App\Models\ProductTransaction;
use App\Models\TicketTransaction;
use Illuminate\Http\Resources\Json\JsonResource;

class LoyaltyRewardPointHistory extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return array
	 */
	public function toArray($request)
	{
		$transactionTimeStamp = null;
		$transactionType = $this->transaction_type;
		$transactionId = $this->transaction_id;

		if ($transactionType == 'event') {
			$transactionTimeStamp = EventTransaction::where('id', $transactionId)->pluck('transaction_timestamp')->first();
		} else if (($transactionType == 'food_and_drink') || ($transactionType == 'merchandise')) {
			$transactionTimeStamp = ProductTransaction::where('id', $transactionId)->pluck('transaction_timestamp')->first();
		} else if ($transactionType == 'membership') {
			$transactionTimeStamp = ConsumerMembershipPackage::where('id', $transactionId)->pluck('transaction_timestamp')->first();
		} else if ($transactionType == 'hospitality') {
			$transactionTimeStamp = HospitalitySuiteTransaction::where('id', $transactionId)->pluck('transaction_timestamp')->first();
		} else if ($transactionType == 'ticket') {
			$transactionTimeStamp = TicketTransaction::where('id', $transactionId)->pluck('transaction_timestamp')->first();
		} else if ($transactionType == 'loyalty_reward') {
			$transactionTimeStamp = LoyaltyRewardTransaction::where('id', $transactionId)->pluck('transaction_timestamp')->first();
		}

		return [
			'id' => $this->id,
			'consumer_id' => $this->consumer_id,
			'transaction_id' => $this->transaction_id,
			'transaction_type' => $this->transaction_type,
			'transaction_type_text' => config('fanslive.TRANSACTION_TYPE_TEXT')[$this->transaction_type],
			'points' => $this->points,
			'transaction_timestamp' => $transactionTimeStamp,
		];
	}
}
