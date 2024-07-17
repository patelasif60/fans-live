<?php


namespace App\Http\Resources\LoyaltyRewardCollectionPoint;
use Illuminate\Http\Resources\Json\JsonResource;

class LoyaltyRewardCollectionPoint extends JsonResource
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
			'loyalty_reward_id' => $this->loyalty_reward_id,
			'collection_point_id' => $this->collection_point_id,
		];
	}

}
