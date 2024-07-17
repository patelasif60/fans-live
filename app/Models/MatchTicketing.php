<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchTicketing extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'match_ticketings';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Disable timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    /**
     * Get a match ticketing available blocks.
     */
    public function availableBlocks()
    {
        return $this->hasMany(\App\Models\MatchTicketingAvailableBlock::class, 'match_ticketing_id');
    }

    /**
     * Get a match ticketing sponsor.
     */
    public function sponsor()
    {
        return $this->hasMany(\App\Models\MatchTicketingSponsor::class, 'match_ticketing_id');
    }

    /**
     * Get a match pricing brands.
     */
    public function pricingBrand()
    {
        return $this->hasMany(\App\Models\MatchTicketingPricingBand::class, 'match_ticketing_id');
    }

    /**
     * Get a match of ticketing membership.
     */
    public function match()
    {
        return $this->belongsTo(\App\Models\Match::class, 'match_id');
    }
}
