<?php

namespace App\Http\Resources\ProductTransaction;

use App\Http\Resources\PurchasedProduct\PurchasedProduct as PurchasedProductResource;
use App\Http\Resources\CollectionPoint\CollectionPoint as CollectionPointResource;
use App\Http\Resources\Match\Match as MatchResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Image;
use QrCode;
use Storage;

class ProductTransaction extends JsonResource
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
        $productTransactionQrcodePath = config('fanslive.IMAGEPATH.product_transaction_qrcode');
        $staffFirstName = '';
        $staffLastName = '';
        if($this->productTransactionCollection && $this->productTransactionCollection->staff_id != NULL && $this->productTransactionCollection->status !== 'New') {
            $staffFirstName = $this->productTransactionCollection->staff->user->first_name;
            $staffLastName = $this->productTransactionCollection->staff->user->last_name;
        }

        $transactionTimestampAgo = '';
        if(isset($this->transaction_timestamp)) {
            $date = convertDateTimezone($this->getRawOriginal('transaction_timestamp'), 'UTC', $this->consumer->time_zone);
            $transactionTimestampAgo = getDateDiff($date, $this->consumer->time_zone);
        }
        return [
            'id'                           => $this->id,
            'customer_first_name'          => $this->consumer->user->first_name,
            'customer_last_name'           => $this->consumer->user->last_name,
            'staff_first_name'             => $staffFirstName,
            'staff_last_name'              => $staffLastName,
            'transaction_id'               => $this->transaction_id,
            'transaction_type'             => 'product',
            'receipt_number'               => $this->receipt_number,
            //'payment_type'                 => $this->payment_type,
            'payment_brand'                => $this->payment_brand,
            'price'                        => formatNumber($this->price),
            'currency'                     => $this->currency,
            'currency_symbol'              => $this->currency === 'GBP' ? '&#163;' : '&#128;',
            'type'                         => $this->type,
            'status'                       => $this->status,
            'result_description'           => $this->result_description,
            'card_details'                 => json_decode($this->card_details),
            'custom_parameters'            => json_decode($this->custom_parameters),
			'transaction_timestamp'        => $this->transaction_timestamp,
            'transaction_timestamp_ago'    => $transactionTimestampAgo,
			'selected_collection_time'     => $this->selected_collection_time,
			'collection_time'              => $this->collection_time,
            'order_number'                 => $this->productTransactionCollection ? $this->productTransactionCollection->id : null,
            'order_collection_status'      => $this->productTransactionCollection ? $this->productTransactionCollection->status : null,
			'order_collected_time'         => ($this->productTransactionCollection && $this->productTransactionCollection->status == 'Collected') ? $this->productTransactionCollection->collected_time : null,
			'is_order_collected'           => ($this->productTransactionCollection && $this->productTransactionCollection->status == 'Collected') ? true : false,
            'collection_point'             => new CollectionPointResource($this->collectionPoint),
			'match'                        => new MatchResource($this->match),
			'purchased_products'           => PurchasedProductResource::collection($this->purchasedProducts),
			'loyalty_points'               => isset($this->loyaltyRewardPoints) ? $this->loyaltyRewardPoints->points : 0,
			// 'qrcode'                       => (string) Image::make(
			// 									QrCode::format('png')->size(300)->generate(json_encode(['url' => 'scan_order', 'transaction_id' => $this->id, 'type' => 'product']))
			// 								)->encode('data-url'),
            'qrcode_url'            => 		$disk->url($productTransactionQrcodePath . $this->id . '.png'),
        ];
    }
}
