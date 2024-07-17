<?php

namespace App\Http\Resources\TravelWarning;

use Illuminate\Http\Resources\Json\JsonResource;

class TravelWarning extends JsonResource
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
            'id'                        => $this->id,
            'text'                      => $this->text,
            'publication_date_time'     => $this->publication_date_time,
            'show_until'                => $this->show_until,
            'color'                     => $this->color,
            'status'                    => $this->status,
        ];
    }
}
