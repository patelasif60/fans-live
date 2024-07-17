<?php

namespace App\Http\Resources\SpecialOffer;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SpecialOfferCollection extends ResourceCollection
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
