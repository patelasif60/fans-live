<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchEvent extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'match_events';

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
     * The match player that belong to the match event.
     */
    public function player()
    {
        return $this->belongsTo(\App\Models\Player::class, 'player_id');
    }

    /**
     * The match substitute player that belong to the match event.
     */
    public function substitutePlayer()
    {
        return $this->belongsTo(\App\Models\Player::class, 'substitute_player_id');
    }

    /**
     * The match that belong to the match event.
     */
    public function match()
    {
        return $this->belongsTo(\App\Models\Match::class, 'match_id');
    }

    /**
     * The team type that belong to the match event.
     */
    public function getTeamType()
    {
        $match = $this->match;
        $teamId = $this->club_id;
        if ($match->home_team_id === $teamId) {
            return 'home';
        }
        if ($match->away_team_id === $teamId) {
            return 'away';
        }
    }
}
