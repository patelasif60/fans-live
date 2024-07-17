<?php

namespace App\Http\Resources\MatchTicketing;

use App\Http\Resources\MatchTicketingMembershipPackage\MatchTicketingMembershipPackage as MatchTicketingMembershipPackageResource;
use App\Http\Resources\MatchTicketingSponsor\MatchTicketingSponsor as MatchTicketingSponsorResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MatchTicketing extends JsonResource
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
            'id'                           => $this->id,
            'maximum_ticket_per_user'      => $this->maximum_ticket_per_user,
            'unavailable_seats'            => $this->unavailable_seats,
            'unavailable_seats_file_name'  => $this->unavailable_seats_file_name,
            'rewards_percentage_override'  => $this->rewards_percentage_override,
            'vat_rate'                     => $this->vat_rate,
            'allow_ticket_returns_resales' => $this->allow_ticket_returns_resales,
            'ticket_resale_fee_type'       => $this->ticket_resale_fee_type,
            'ticket_resale_fee_amount'     => $this->ticket_resale_fee_amount,
            'sponsors'                     => MatchTicketingSponsorResource::collection($this->sponsor),
            'on_sale_dates'                => MatchTicketingMembershipPackageResource::collection($this->match->ticketingMembership),
        ];
    }
}
