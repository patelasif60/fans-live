<?php

namespace App\Http\Resources\SpecialOffer;

use Illuminate\Http\Resources\Json\JsonResource;

class SpecialOffer extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'club_id' => $this->club_id,
            'type' => $this->type,
            'image' => $this->image,
            'image_file_name' => $this->image_file_name,
            'status' => $this->status,
            'is_restricted_to_over_age' => $this->is_restricted_to_over_age,
            'discount_type' => $this->discount_type,
            'related_to' => 'special_offer'
        ];
    }
}
