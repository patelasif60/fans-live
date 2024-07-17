<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipPackage extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'membership_packages';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

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
        return formatNumber($this->price + $this->vatAmount);
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
