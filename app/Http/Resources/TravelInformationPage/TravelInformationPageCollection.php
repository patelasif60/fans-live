<?php

namespace App\Http\Resources\TravelInformationPage;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TravelInformationPageCollection extends ResourceCollection
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
