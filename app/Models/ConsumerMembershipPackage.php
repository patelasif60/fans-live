<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsumerMembershipPackage extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'consumer_membership_package';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    /**
     * The database table used by the model.
     *
     * @var date
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * Get a membership package.
     */
    public function membershipPackage()
    {
        return $this->belongsTo(\App\Models\MembershipPackage::class);
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
        return formatNumber($this->price + $this->vatAmount);
    }

    /**
     * Get consumer
     */
    public function consumer()
    {
        return $this->belongsTo(\App\Models\Consumer::class, 'consumer_id');
    }

    /**
     * Get club
     */
    public function club()
    {
        return $this->belongsTo(\App\Models\Club::class, 'club_id');
    }

    /**
     * Get Loyalty Reward Points
     */
    public function loyaltyRewardPoints()
    {
        return $this->hasOne(\App\Models\LoyaltyRewardPointHistory::class, 'transaction_id')->where('transaction_type', 'membership');
    }

    /**
     * Get price.
     */
    public function getPriceAttribute()
    {
        return formatNumber($this->attributes['price']);
    }
}
