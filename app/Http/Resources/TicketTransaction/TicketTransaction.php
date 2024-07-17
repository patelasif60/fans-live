<?php

namespace App\Http\Resources\TicketTransaction;

use App\Http\Resources\BookedTicket\BookedTicket as BookedTicketResource;
use App\Http\Resources\BookedTicket\BookedTicketCollection;
use App\Http\Resources\Match\Match as MatchResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketTransaction extends JsonResource
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
            'transaction_type'             => 'match_ticket',
            'transaction_id'               => $this->transaction_id,
            'receipt_number'               => $this->receipt_number,
//            'payment_type'                 => $this->payment_type,
            'payment_brand'                => $this->payment_brand,
            'price'                        => formatNumber($this->price),
            'currency'                     => $this->currency,
            'currency_symbol'              => $this->currency === 'GBP' ? '&#163;' : '&#128;',
            'status'                       => $this->status,
            'result_description'           => $this->result_description,
            'card_details'                 => json_decode($this->card_details),
            'custom_parameters'            => json_decode($this->custom_parameters),
            'transaction_timestamp'        => $this->transaction_timestamp,
            'match'                        => new MatchResource($this->match),
            'booked_tickets'               => BookedTicketCollection::make($this->bookedTickets)->checkWalletDetailsFlag(false),
            'loyalty_points'               => isset($this->loyaltyRewardPoints) ? $this->loyaltyRewardPoints->points : 0,
            'ticket_purchase_web_view_url' => route('frontend.pick.block', ['clubId' => $this->club_id, 'matchId' => $this->match_id, 'consumerId' => $this->consumer_id, 'totalseat' => 0]),
        ];
    }
}
