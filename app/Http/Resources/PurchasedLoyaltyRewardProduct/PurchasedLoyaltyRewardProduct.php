<?php

namespace App\Http\Resources\PurchasedLoyaltyRewardProduct;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\LoyaltyRewardOption\LoyaltyRewardOption as LoyaltyRewardOptionResource;

class PurchasedLoyaltyRewardProduct extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return array
	 */
	public function toArray($request)
	{
		return [
			'id' => $this->id,
			'loyalty_reward_transaction_id' => $this->loyalty_reward_transaction_id,
			'loyalty_reward_id' => $this->loyalty_reward_id,
			'loyalty_reward_name' => $this->loyaltyRewardProduct->title,
			'options' => LoyaltyRewardOptionResource::collection($this->options),
			'quantity' => $this->quantity,
			'per_quantity_points' => $this->per_quantity_points,
			'per_quantity_additional_options_point' => $this->per_quantity_additional_options_point,
			'total_points' => $this->total_points,
		];
	}
}
