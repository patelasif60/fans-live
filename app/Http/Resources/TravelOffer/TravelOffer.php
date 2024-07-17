<?php

namespace App\Http\Resources\TravelOffer;

use Illuminate\Http\Resources\Json\JsonResource;

class TravelOffer extends JsonResource
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
            'id'                 => $this->id,
            'title'              => $this->title,
            'content'            => $this->content,
            'thumbnail'          => $this->thumbnail,
            'banner'             => $this->banner,
            'icon'               => $this->icon,
            'button_colour'      => $this->button_colour,
            'button_text_colour' => $this->button_text_colour,
            'button_text'        => $this->button_text,
            'button_url'         => $this->button_url,
            'publication_date'   => $this->publication_date,
            'show_until'         => $this->show_until,
        ];
    }
}
