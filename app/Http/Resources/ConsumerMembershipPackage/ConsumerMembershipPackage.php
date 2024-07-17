<?php

namespace App\Http\Resources\ConsumerMembershipPackage;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Image;
use QrCode;
use Storage;

class ConsumerMembershipPackage extends JsonResource
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
    	$disk = Storage::disk('s3');
    	$consumerMembershipPackageQrcodePath = config('fanslive.IMAGEPATH.consumer_membership_package_qrcode');
        $currentDateTime = Carbon::now()->startOfDay();
    	$membershipExpiryNotificationDateTime = Carbon::parse($this->transaction_timestamp)->addMonths($this->duration)->startOfDay();
    	$isMembershipExpired = ($currentDateTime->gt($membershipExpiryNotificationDateTime)) ? 1 : 0;

        return [
            'id'                             => $this->id,
            'transaction_id'                 => $this->transaction_id,
            'transaction_type'               => 'membership',
            'receipt_number'                 => $this->receipt_number,
            'membership_package_id'          => $this->membership_package_id,
            'membership_package_title'       => $this->membershipPackage->title,
            'membership_package_benefits'    => $this->membershipPackage->benefits,
            'membership_package_icon'        => $this->membershipPackage->icon,
            'membership_package_expiry_date' => Carbon::parse($this->transaction_timestamp)->addMonths($this->duration)->toDateString(),
            'is_membership_expired'			 => $isMembershipExpired,
            'payment_brand'                  => $this->payment_brand,
            'duration'                       => $this->duration,
            'currency'                       => $this->currency,
            'currency_symbol'                => $this->currency === 'GBP' ? '&#163;' : '&#128;',
            'price'                          => formatNumber($this->price),
            'vat_percent'                    => $this->vat_rate.'%',
            'vat_amount'                     => $this->vat_amount,
            'final_price'                    => formatNumber($this->final_price),
            'status'                         => $this->status,
            'result_description'             => $this->result_description,
            'is_active'                      => $this->is_active,
            'card_details'                   => json_decode($this->card_details),
            'custom_parameters'              => json_decode($this->custom_parameters),
            'transaction_timestamp'          => $this->transaction_timestamp,
            'loyalty_points'                 => isset($this->loyaltyRewardPoints) ? $this->loyaltyRewardPoints->points : 0,
            'qrcode'                         => (string) Image::make(
                                                    QrCode::format('png')->size(300)->generate(json_encode(['consumer_membership_package_id' => $this->id, 'consumer_id' => $this->consumer_id]))
                                                )->encode('data-url'),
            'qrcode_url'            		 => $disk->url($consumerMembershipPackageQrcodePath . $this->id . '.png'),
        ];
    }
}
