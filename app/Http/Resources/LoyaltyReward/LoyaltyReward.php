<?php

namespace App\Http\Resources\LoyaltyReward;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\LoyaltyRewardCollectionPoint\LoyaltyRewardCollectionPoint as LoyaltyRewardCollectionPointResource;
use App\Http\Resources\LoyaltyRewardOption\LoyaltyRewardOption as LoyaltyRewardOptionResource;

class LoyaltyReward extends JsonResource
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
			'club_id' => $this->club_id,
			'title' => $this->title,
			'description' => $this->description,
			'image' => $this->image,
			'image_file_name' => $this->image_file_name,
			'price_in_points' => $this->price_in_points,
			'status' => $this->status,
			'options' => LoyaltyRewardOptionResource::collection($this->loyaltyRewardsOptions),
			'collection_points' => LoyaltyRewardCollectionPointResource::collection($this->loyaltyRewardsCollectionPoints),
		];
	}

}
