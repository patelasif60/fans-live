<?php


namespace App\Http\Resources\HospitalitySuiteTransaction;

use App\Http\Resources\BookedHospitalitySuite\BookedHospitalitySuite as BookedHospitalitySuiteResource;
use App\Http\Resources\BookedHospitalitySuite\BookedHospitalitySuiteCollection;
use App\Http\Resources\HospitalitySuite\HospitalitySuite as HospitalitySuiteResource;
use App\Http\Resources\Match\Match as MatchResource;
use Illuminate\Http\Resources\Json\JsonResource;

class HospitalitySuiteTransaction extends JsonResource
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
			'match_id'					   => $this->match_id,
			'transaction_id'               => $this->transaction_id,
			'receipt_number'               => $this->receipt_number,
//			'payment_type'                 => $this->payment_type,
			'payment_brand'                => $this->payment_brand,
			'price'                        => formatNumber($this->price),
			'currency'                     => $this->currency,
			'currency_symbol'              => $this->currency === 'GBP' ? '&#163;' : '&#128;',
			'status'                       => $this->status,
			'result_description'           => $this->result_description,
			'card_details'                 => json_decode($this->card_details),
			'custom_parameters'            => json_decode($this->custom_parameters),
			'transaction_timestamp'        => $this->transaction_timestamp,
			'hospitality_suite'            => new HospitalitySuiteResource($this->hospitalitySuite,$this->match_id),
			'match'				  		   => new MatchResource($this->match),
			'loyalty_points'               => isset($this->loyaltyRewardPoints) ? $this->loyaltyRewardPoints->points : 0,
			'booked_hospitality_suite_tickets' => BookedHospitalitySuiteCollection::make($this->bookedHospitalitySuits)->checkWalletDetailsFlag(false),
		];
	}

}
