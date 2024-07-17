<?php

namespace App\Http\Resources\MatchTicketNotification;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MatchTicketingNotificationCollection extends ResourceCollection
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
