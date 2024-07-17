<?php

namespace App\Http\Resources\MatchPlayerVoting;

use App\Models\MatchPlayerVoting as MatchPlayerVotingModel;
use Illuminate\Http\Resources\Json\JsonResource;

class MatchPlayerVoting extends JsonResource
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
            'id'            => $this->id,
            'player_id'     => $this->player_id,
            'club_logo'     => $this->club->logo,
            'team_type'     => $this->team_type,
            'type'          => $this->type,
            'name'          => $this->player->name,
            'shirt_number'  => $this->shirt_number,
            'is_substitute' => $this->is_substitute,
            'votes'         => MatchPlayerVotingModel::where('match_id', $this->match_id)->where('player_id', $this->player_id)->get()->count(),
        ];
    }
}
