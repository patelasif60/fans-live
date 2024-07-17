<?php

namespace App\Http\Resources\HospitalitySuite;

use Illuminate\Http\Resources\Json\JsonResource;

class HospitalitySuiteBrief extends JsonResource
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
            'short_description'           => $this->short_description,
            'long_description'            => $this->long_description,
        ];
    }
}
