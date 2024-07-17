<?php

namespace App\Http\Resources\StadiumGeneralSetting;

use Illuminate\Http\Resources\Json\JsonResource;

class StadiumGeneralSetting extends JsonResource
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
            'id'        => $this->id,
            'name'      => $this->name,
            'address'   => $this->address,
            'address_2' => $this->address_2,
            'town'      => $this->town,
            'postcode'  => $this->postcode,
            'latitude'  => $this->latitude,
            'longitude' => $this->longitude,
            'image'     => $this->image,
            'is_using_allocated_seating' => $this->is_using_allocated_seating,
            'number_of_seats' => $this->number_of_seats,
        ];
    }
}
