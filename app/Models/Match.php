<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\BookedTicket;

class Match extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'matches';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get the competition detail.
     */
    public function competition()
    {
        return $this->belongsTo(\App\Models\Competition::class);
    }

    /**
     * Get a match event.
     */
    public function event()
    {
        return $this->hasMany(\App\Models\MatchEvent::class, 'match_id')->orderBy('minute', 'ASC');
    }

    /**
     * Get a match hospitality.
     */
    public function hospitality()
    {
        return $this->hasOne(\App\Models\MatchHospitality::class);
    }

    /**
     * Get a match ticketing.
     */
    public function ticketing()
    {
        return $this->hasOne(\App\Models\MatchTicketing::class);
    }

    /**
     * Get a match ticketing membership.
     */
    public function ticketingMembership()
    {
        return $this->hasMany(\App\Models\MatchTicketingMembershipPackage::class, 'match_id');
    }

    /**
     * Get a match hospitality membership.
     */
    public function hospitalityMembership()
    {
        return $this->hasMany(\App\Models\MatchHospitalityMembershipPackage::class, 'match_id');
    }

    /**
     * Get a match player.
     */
    public function player()
    {
        return $this->hasMany(\App\Models\MatchPlayer::class, 'match_id');
    }

    /**
     * Get a match home players.
     */
    public function getClubPlayersByType($clubId, $type)
    {
        return $this->player()->where('club_id', $clubId)->where('type', $type)->get();
    }

    /**
     * Get home team detail.
     */
    public function homeTeam()
    {
        return $this->belongsTo(\App\Models\Club::class, 'home_team_id');
    }

    /**
     * Get away team detail.
     */
    public function awayTeam()
    {
        return $this->belongsTo(\App\Models\Club::class, 'away_team_id');
    }

    /**
     * Get match polls.
     */
    public function poll()
    {
        return $this->hasMany(\App\Models\Poll::class, 'associated_match');
    }

    /**
     * Get ticket transactions.
     */
    public function ticketTransactions()
    {
        return $this->hasMany(\App\Models\TicketTransaction::class, 'match_id');
    }
    /**
     * Get available tickets.
     */
    public function getMatchTickets($match, $matchId)
    {
        return BookedTicket::whereIn('ticket_transaction_id',$match->ticketTransactions->where('match_id', $matchId)->pluck('id'));//->max('seat');
    }

}
