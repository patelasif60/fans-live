<?php

namespace App\Http\Resources\MatchTicketNotification;

use Illuminate\Http\Resources\Json\JsonResource;

class MatchTicketNotification extends JsonResource
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
            'id'               => $this->id,
            'consumer_id'      => $this->consumer_id,
            'club_id'          => $this->club_id,
            'match_id'         => $this->match_id,
            'stadium_block_id' => $this->stadium_block_id,
            'reason'           => $this->reason,
            'is_notified'      => $this->is_notified,
            'created_at'       => $this->created_at,
            'updated_at'       => $this->updated_at,
        ];
    }
}
