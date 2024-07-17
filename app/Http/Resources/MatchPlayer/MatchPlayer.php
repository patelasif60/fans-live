<?php

namespace App\Http\Resources\MatchPlayer;

use App\Http\Resources\MatchEvent\MatchEvent as MatchEventResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MatchPlayer extends JsonResource
{
    /**
     * The resource instance.
     *
     * @var mixed
     */
    public $resource;

    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $matchId;

    /**
     * Create a new resource instance.
     *
     * @param mixed $resource
     *
     * @return void
     */
    public function __construct($resource, $matchId)
    {
        // Ensure you call the parent constructor
        parent::__construct($resource);
        $this->resource = $resource;
        $this->matchId = $matchId;
    }

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
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'team_type'     => $this->getTeamType(),
            'type'          => $this->type,
            'name'          => $this->player->name,
            'shirt_number'  => $this->shirt_number,
            'is_substitute' => $this->is_substitute,
            'events'        => MatchEventResource::collection($this->player->getEventsByMatch($this->matchId)),
        ];
    }
}
