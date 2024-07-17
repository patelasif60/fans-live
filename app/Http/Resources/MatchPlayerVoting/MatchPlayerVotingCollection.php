<?php

namespace App\Http\Resources\MatchPlayerVoting;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MatchPlayerVotingCollection extends ResourceCollection
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
