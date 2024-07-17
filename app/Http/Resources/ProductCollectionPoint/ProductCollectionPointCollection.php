<?php

namespace App\Http\Resources\ProductCollectionPoint;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollectionPointCollection extends ResourceCollection
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
