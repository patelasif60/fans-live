<?php

namespace App\Http\Resources\ConsumerCard;

use Illuminate\Http\Resources\Json\JsonResource;

class ConsumerCard extends JsonResource
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
            'token'         => $this->token,
            'truncated_pan' => $this->truncated_pan,
            'brand'         => $this->brand,
        ];
    }
}
