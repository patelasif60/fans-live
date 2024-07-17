<?php

namespace App\Http\Resources\Standing;

use Illuminate\Http\Resources\Json\JsonResource;

class Standing extends JsonResource
{
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
            'id'              => $this->id,
            'club_id'         => $this->club_id,
            'competition_id'  => $this->competition_id,
            'season_id'       => $this->season_id,
            'stage'           => $this->stage,
            'type'            => $this->type,
            'group'           => $this->group,
            'position'        => $this->position,
            'club'            => $this->club->name,
            'played_games'    => $this->played_games,
            'won'             => $this->won,
            'draw'            => $this->draw,
            'lost'            => $this->lost,
            'points'          => $this->points,
            'goal_for'        => $this->goal_for,
            'goal_against'    => $this->goal_against,
            'goal_difference' => $this->goal_difference,
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
        ];
    }
}
