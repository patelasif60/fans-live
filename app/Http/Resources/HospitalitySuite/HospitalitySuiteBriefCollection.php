<?php

namespace App\Http\Resources\HospitalitySuite;

use Illuminate\Http\Resources\Json\ResourceCollection;

class HospitalitySuiteBriefCollection extends ResourceCollection
{
	/**
	 * Transform the resource collection into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function toArray($request)
	{
		 return [
            'data' => $this->collection,
        ];
	}
}
