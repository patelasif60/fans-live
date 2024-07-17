<?php

namespace App\Http\Resources\LoyaltyRewardPointHistory;


use Illuminate\Http\Resources\Json\ResourceCollection;

class LoyaltyRewardPointHistoryCollection extends ResourceCollection
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
