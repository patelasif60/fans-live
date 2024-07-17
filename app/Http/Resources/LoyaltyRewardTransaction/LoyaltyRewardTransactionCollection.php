<?php


namespace App\Http\Resources\LoyaltyRewardTransaction;

use Illuminate\Http\Resources\Json\ResourceCollection;

class LoyaltyRewardTransactionCollection extends ResourceCollection
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
