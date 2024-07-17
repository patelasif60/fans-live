<?php

namespace App\Http\Resources\PurchasedLoyaltyRewardProduct;

use Illuminate\Http\Resources\Json\ResourceCollection;
class PurchasedLoyaltyRewardProductCollection extends ResourceCollection
{
	/**
	 * Transform the resource collection into an array.
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return array
	 */
	public function toArray($request)
	{
		return [
			'data' => $this->collection,
		];
	}
}
