<?php

namespace App\Http\Resources\StadiumEntrance;

use Illuminate\Http\Resources\Json\ResourceCollection;

class StadiumEntranceCollection extends ResourceCollection
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
