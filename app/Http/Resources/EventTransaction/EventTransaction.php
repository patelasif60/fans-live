<?php


namespace App\Http\Resources\EventTransaction;

use App\Http\Resources\BookedEvent\BookedEvent as BookedEventResource;
use App\Http\Resources\Event\Event as EventResource;
use App\Http\Resources\BookedEvent\BookedEventCollection;

use Illuminate\Http\Resources\Json\JsonResource;

class EventTransaction extends JsonResource
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
			'transaction_id'               => $this->transaction_id,
			'transaction_type'             => 'event_ticket',
			'receipt_number'               => $this->receipt_number,
		//	'payment_type'                 => $this->payment_type,
			'payment_brand'                => $this->payment_brand,
			'price'                        => formatNumber($this->price),
			'currency'                     => $this->currency,
			'currency_symbol'              => $this->currency === 'GBP' ? '&#163;' : '&#128;',
			'status'                       => $this->status,
			'result_description'           => $this->result_description,
			'card_details'                 => json_decode($this->card_details),
			'custom_parameters'            => json_decode($this->custom_parameters),
			'transaction_timestamp'        => $this->transaction_timestamp,
			'loyalty_points'               => isset($this->loyaltyRewardPoints) ? $this->loyaltyRewardPoints->points : 0,
			'event'                        => new EventResource($this->event),
			'booked_event_tickets'         => BookedEventCollection::make($this->bookedEvents)->checkWalletDetailsFlag(false),
		];
	}

}
