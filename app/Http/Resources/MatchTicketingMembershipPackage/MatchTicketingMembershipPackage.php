<?php

namespace App\Http\Resources\MatchTicketingMembershipPackage;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MatchTicketingMembershipPackage extends JsonResource
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
            'id'                    	=> $this->id,
            'membership_package_id' 	=> $this->membership_package_id,
            'membership_package_name'	=> $this->membershipPackage->title,
            'date'                  	=> $this->date,
        ];
    }
}
