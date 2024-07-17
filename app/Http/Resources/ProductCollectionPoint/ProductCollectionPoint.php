<?php

namespace App\Http\Resources\ProductCollectionPoint;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductCollectionPoint extends JsonResource
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
			'product_id' => $this->product_id,
			'collection_point_id' => $this->collection_point_id,
		];
	}
}
