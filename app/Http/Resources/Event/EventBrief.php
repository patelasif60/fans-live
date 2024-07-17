<?php

namespace App\Http\Resources\Event;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class EventBrief extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                          => $this->id,
            'title'                       => $this->title,
            'description'                 => $this->description,
            'location'                    => $this->location,
            'date_time'                   => $this->date_time,
        ];
    }
}
