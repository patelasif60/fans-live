<?php

namespace App\Http\Resources\LoyaltyRewardTransaction;

use Image;
use QrCode;
use Storage;
use App\Http\Resources\Match\Match as MatchResource;
use App\Http\Resources\CollectionPoint\CollectionPoint as CollectionPointResource;
use App\Http\Resources\PurchasedLoyaltyRewardProduct\PurchasedLoyaltyRewardProduct as PurchasedLoyaltyRewardProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class LoyaltyRewardTransaction extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return array
	 */
	public function toArray($request)
	{
		$disk = Storage::disk('s3');
		$loyaltyRewardTransactionQrcodePath = config('fanslive.IMAGEPATH.loyalty_reward_transaction_qrcode');
		$staffFirstName = '';
        $staffLastName = '';
        if($this->loyaltyRewardTransactionCollection && $this->loyaltyRewardTransactionCollection->staff_id != NULL && $this->loyaltyRewardTransactionCollection->status !== 'New') {
			$staffFirstName = $this->loyaltyRewardTransactionCollection->staff->user->first_name;
            $staffLastName = $this->loyaltyRewardTransactionCollection->staff->user->last_name;
        }

        $transactionTimestampAgo = '';
        if(isset($this->transaction_timestamp)) {
            $date = convertDateTimezone($this->getRawOriginal('transaction_timestamp'), 'UTC', $this->consumer->time_zone);
            $transactionTimestampAgo = getDateDiff($date, $this->consumer->time_zone);
        }

		return [
			'id' => $this->id,
			'club_id' => $this->club_id,
			'consumer_id' => $this->consumer_id,
			'customer_first_name' => $this->consumer->user->first_name,
            'customer_last_name' => $this->consumer->user->last_name,
            'staff_first_name' => $staffFirstName,
            'staff_last_name' => $staffLastName,
			'transaction_type' => 'loyalty_reward',
			'receipt_number' => $this->receipt_number,
			'points' => $this->points,
			'status' => $this->status,
			'collection_point_id' => $this->collection_point_id,
			'transaction_timestamp' => $this->transaction_timestamp,
			'transaction_timestamp_ago' => $transactionTimestampAgo,
            'selected_collection_time'     => $this->selected_collection_time,
			'collection_time'              => $this->collection_time,
			'order_number'                 => $this->loyaltyRewardTransactionCollection ? $this->loyaltyRewardTransactionCollection->id : null,
            'order_collection_status'      => $this->loyaltyRewardTransactionCollection ? $this->loyaltyRewardTransactionCollection->status : null,
			'order_collected_time'         => ($this->loyaltyRewardTransactionCollection && $this->loyaltyRewardTransactionCollection->status == 'Collected') ? $this->loyaltyRewardTransactionCollection->collected_time : null,
			'is_order_collected'           => ($this->loyaltyRewardTransactionCollection && $this->loyaltyRewardTransactionCollection->status == 'Collected') ? true : false,
			'collection_point' => new CollectionPointResource($this->collectionPoint),
            'match' => new MatchResource($this->match),
            'purchased_loyalty_reward_products' => PurchasedLoyaltyRewardProductResource::collection($this->purchasedLoyaltyRewardProducts),
            'loyalty_points' => isset($this->loyaltyRewardPoints) ? $this->loyaltyRewardPoints->points : 0,
            // 'qrcode' => (string) Image::make(QrCode::format('png')->size(300)->generate(json_encode(['url' => 'scan_order'					, 'transaction_id' => $this->id, 'type' => 'loyalty_reward']))
            //                                 )->encode('data-url'),
            'qrcode_url'            => $disk->url($loyaltyRewardTransactionQrcodePath . $this->id . '.png'),
		];
	}
}
