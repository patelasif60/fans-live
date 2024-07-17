<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellMatchTicket extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sell_match_ticket';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    /**
     * Enable timestamps.
     *
     * @var array
     */
    public $timestamps = true;

	/**
	 * The booked ticket that belong to the ticket.
	 */
	public function bookedTicket()
	{
		return $this->belongsTo(\App\Models\BookedTicket::class, 'booked_ticket_id');
	}

}
