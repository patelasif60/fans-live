<?php

namespace App\Http\Resources\PricingBand;

use Illuminate\Http\Resources\Json\JsonResource;

class PricingBand extends JsonResource
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
            'id'             => $this->id,
            'club_id'        => $this->club_id,
            'display_name'   => $this->display_name,
            'internal_name'  => $this->internal_name,
            'price'          => formatNumber($this->price),
            'vat_rate'       => $this->vat_rate,
            'final_price'    => formatNumber($this->price + (($this->price * $this->vat_rate) / 100)),
            'seat'           => $this->seat,
            'seat_file_name' => $this->seat_file_name
        ];
    }
}
