<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingBand extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pricing_bands';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
    * Get the Pricing Band Seats.
    */
    public function pricingBandSeat()
    {
        return $this->hasMany(\App\Models\PricingBandSeat::class);
    }

    /**
     * Get vat amount.
     */
    public function getVatAmountAttribute()
    {
        return ($this->price * $this->vat_rate) / 100;
    }

    /**
     * Get final price.
     */
    public function getFinalPriceAttribute()
    {
        return $this->price + $this->vatAmount;
    }

    /**
     * Get price.
     */
    public function getPriceAttribute()
    {
        return formatNumber($this->attributes['price']);
    }

    /**
     * Get vat rate.
     */
    public function getVatRateAttribute()
    {
        return formatNumber($this->attributes['vat_rate']);
    }
}
