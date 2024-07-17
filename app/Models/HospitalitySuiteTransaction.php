<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HospitalitySuiteTransaction extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'hospitality_suite_transactions';
	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id'];

	/**
	 * The hospitality Suite that belong to the hospitality transaction.
	 */
	public function hospitalitySuite()
	{
		return $this->belongsTo(\App\Models\HospitalitySuite::class, 'hospitality_suite_id');
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
     * The match that belong to the product transaction.
     */
    public function match()
    {
        return $this->belongsTo(\App\Models\Match::class, 'match_id');
    }

    /**
     * Get Loyalty Reward Points
     */
    public function loyaltyRewardPoints()
    {
        return $this->hasOne(\App\Models\LoyaltyRewardPointHistory::class, 'transaction_id')->where('transaction_type', 'hospitality');
    }

    /**
     * The match that belong to the hospitality suite transaction.
     */
    public function bookedHospitalitySuits()
    {
        return $this->hasMany(\App\Models\BookedHospitalitySuite::class, 'hospitality_suite_transaction_id');
    }

    /**
     * Get price.
     */
    public function getPriceAttribute()
    {
        return formatNumber($this->attributes['price']);
    }
}
