<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchTicketNotification extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'match_ticket_notifications';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_notified' => 'boolean',
    ];

    /**
     * Get consumer.
     */
    public function consumer()
    {
        return $this->belongsTo(Consumer::class, 'consumer_id');
    }

    /**
     * Get match.
     */
    public function match()
    {
        return $this->belongsTo(Match::class, 'match_id');
    }

    /**
     * Get stadium block.
     */
    public function stadiumBlock()
    {
        return $this->belongsTo(StadiumBlock::class, 'stadium_block_id');
    }
}
