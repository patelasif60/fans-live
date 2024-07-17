<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchTicketingAvailableBlock extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'match_ticketing_available_blocks';

    /**
     * Disable timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'match_ticketing_id', 'block_id',
    ];
}
