<?php

namespace App\Http\Resources\LoyaltyRewardCollectionPoint;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LoyaltyRewardCollectionPointCollection extends ResourceCollection
{
	/**
	 * Transform the resource collection into an array.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return array
	 */
	public function toArray($request)
	{
		return [
			'data' => $this->collection,
		];
	}

}
