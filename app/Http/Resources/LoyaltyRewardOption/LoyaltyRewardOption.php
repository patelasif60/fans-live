<?php


namespace App\Http\Resources\LoyaltyRewardOption;

use Illuminate\Http\Resources\Json\JsonResource;

class LoyaltyRewardOption extends JsonResource
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
			'loyalty_reward_id' => $this->loyalty_reward_id,
			'name' => $this->name,
			'additional_point' => $this->additional_point,
		];
	}

}
