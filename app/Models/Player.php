<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'players';

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
     * The events that belong to the match player.
     */
    public function events()
    {
        return $this->hasMany(\App\Models\MatchEvent::class, 'player_id');
    }

    /**
     * The events that belong to the match player of particular match.
     */
    public function getEventsByMatch($matchId)
    {
        return $this->events()->where('match_id', $matchId)->get();
    }
}
