<?php

namespace App\Http\Resources\TravelInformationPageContent;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TravelInformationPageContentCollection extends ResourceCollection
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
