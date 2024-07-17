<?php

namespace App\Http\Resources\Standing;

use Illuminate\Http\Resources\Json\ResourceCollection;

class StandingCollection extends ResourceCollection
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
