<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketingUnavailableSeat extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ticketing_unavailable_seats';

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
     * Get a match ticketing.
     */
    public function matchTicketing()
    {
        return $this->belongsTo(\App\Models\MatchTicketing::class, 'match_ticketing_id');
    }
}
