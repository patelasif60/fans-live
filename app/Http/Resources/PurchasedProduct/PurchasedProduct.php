<?php

namespace App\Http\Resources\PurchasedProduct;

use App\Http\Resources\ProductOption\ProductOption as ProductOptionResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class PurchasedProduct extends JsonResource
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    /**
     * Destory/Unset object variables.
     *
     * @return void
     */
    public function __destruct()
    {

    }

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
            'id'                                        => $this->id,
            'product_transaction_id'                    => $this->product_transaction_id,
            'product_id'                                => $this->product_id,
            'product_name'                              => $this->product->title,
            'options'                                   => ProductOptionResource::collection($this->options),
            'quantity'                                  => $this->quantity,
            'per_quantity_price'                        => formatNumber($this->per_quantity_price),
            'per_quantity_additional_options_cost'      => $this->per_quantity_additional_options_cost,
            'total_price'                               => formatNumber($this->total_price),
            'transaction_timestamp'                     => $this->transaction_timestamp,
        ];
    }
}
