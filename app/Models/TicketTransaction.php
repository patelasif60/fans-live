<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketTransaction extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ticket_transactions';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The match that belong to the ticket transaction.
     */
    public function match()
    {
        return $this->belongsTo(\App\Models\Match::class, 'match_id');
    }

    /**
     * The match that belong to the ticket transaction.
     */
    public function bookedTickets()
    {
        return $this->hasMany(\App\Models\BookedTicket::class, 'ticket_transaction_id');
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
        return $this->hasOne(\App\Models\LoyaltyRewardPointHistory::class, 'transaction_id')->where('transaction_type', 'ticket');
    }

    /**
     * Get price.
     */
    public function getPriceAttribute()
    {
        return formatNumber($this->attributes['price']);
    }
}
