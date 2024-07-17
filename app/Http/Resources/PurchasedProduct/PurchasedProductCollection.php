<?php

namespace App\Http\Resources\PurchasedProduct;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PurchasedProductCollection extends ResourceCollection
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
