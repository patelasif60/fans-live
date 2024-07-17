<?php

namespace App\Http\Resources\MatchPlayer;

use App\Http\Resources\MatchPlayer\MatchPlayer as MatchPlayerResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MatchPlayerCollection extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    protected $matchId;

    /**
     * Set match Id.
     *
     * @param  $totalOptionsCount
     *
     * @return int
     */
    public function setMatchId($matchId)
    {
        $this->matchId = $matchId;

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
        return $this->collection->map(function (MatchPlayerResource $resource) use ($request) {
            return $resource->setMatchId($this->matchId)->toArray($request);
        })->all();
    }
}
