<?php

namespace App\Http\Resources\HospitalityDietaryOptions;

use Illuminate\Http\Resources\Json\JsonResource;

class HospitalityDietaryOptions extends JsonResource
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
            'id'        => $this->id,
            'hospitality_suite_id'     => $this->hospitality_suite_id,
            'option_name'     => $this->option_name,
        ];
    }
}
