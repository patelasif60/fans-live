<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventTransaction extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'event_transactions';
	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id'];

	/**
	 * The event that belong to the event transaction.
	 */
	public function event()
	{
		return $this->belongsTo(\App\Models\Event::class, 'event_id');
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
        return $this->hasOne(\App\Models\LoyaltyRewardPointHistory::class, 'transaction_id')->where('transaction_type', 'event');
    }

    /**
     * The match that belong to the event transaction.
     */
    public function bookedEvents()
    {
        return $this->hasMany(\App\Models\BookedEvent::class, 'event_transaction_id');
    }

    /**
     * Get price.
     */
    public function getPriceAttribute()
    {
        return formatNumber($this->attributes['price']);
    }
}
