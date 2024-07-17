<?php

namespace App\Http\Resources\ProductOption;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductOption extends JsonResource
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
			'product_id' => $this->product_id,
			'additional_cost' => $this->additional_cost,
			'final_additional_cost' => $this->additional_cost + (($this->additional_cost * $this->product->vat_rate) /100),
			'name' => $this->name,
		];
	}
}
