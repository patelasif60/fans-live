<?php

namespace App\Http\Resources\PollOption;

use App\Http\Resources\PollOption\PollOption as PollOptionResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PollOptionCollection extends ResourceCollection
{
    /**
     * Total options count.
     *
     * @param  $totalOptionsCount
     *
     * @return int
     */
    public function totalOptionsCount($totalOptionsCount)
    {
        $this->totalOptionsCount = $totalOptionsCount;

        return $this;
    }

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
            'data' => $this->collection->map(function (PollOptionResource $resource) use ($request) {
                return $resource->totalOptionsCount($this->totalOptionsCount)->toArray($request);
            })->all(),
        ];
    }
}
