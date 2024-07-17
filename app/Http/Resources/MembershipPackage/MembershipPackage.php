<?php

namespace App\Http\Resources\MembershipPackage;

use Illuminate\Http\Resources\Json\JsonResource;

class MembershipPackage extends JsonResource
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
            'id'                          => $this->id,
            'club_id'                     => $this->club_id,
            'title'                       => $this->title,
            'benefits'                    => $this->benefits,
            'membership_duration'         => $this->membership_duration,
            'rewards_percentage_override' => $this->rewards_percentage_override,
            'icon'                        => $this->icon,
            'price'                       => formatNumber($this->price),
            'final_price'                 => formatNumber($this->final_price),
            'vat_rate'                    => $this->vat_rate,
            'vat_amount'                  => $this->vat_amount,
        ];
    }
}
