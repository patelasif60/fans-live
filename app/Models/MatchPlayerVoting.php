<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchPlayerVoting extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'match_player_voting';

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
     * Player.
     */
    public function player()
    {
        return $this->belongsTo(\App\Models\Player::class, 'player_id');
    }
}
