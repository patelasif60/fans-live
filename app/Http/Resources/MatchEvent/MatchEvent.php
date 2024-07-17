<?php

namespace App\Http\Resources\MatchEvent;

use Illuminate\Http\Resources\Json\JsonResource;

class MatchEvent extends JsonResource
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
            'id'                     => $this->id,
            'minute'                 => $this->minute,
            'team_type'              => $this->getTeamType(),
            'event_type'             => $this->event_type,
            'player_name'            => $this->player->name,
            'minute'                 => $this->minute,
            'extra_time'             => $this->extra_time,
            'action_replay_video'    => $this->action_replay_video,
            'substitute_player_name' => @$this->substitutePlayer->name,
        ];
    }
}
