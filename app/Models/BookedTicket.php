<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookedTicket extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'booked_tickets';

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
     * The stadium block seat that belong to the booked ticket.
     */
    public function stadiumBlockSeat()
    {
        return $this->belongsTo(\App\Models\StadiumBlockSeat::class, 'stadium_block_seat_id');
    }

    /**
     * The pricing band that belong to the booked ticket.
     */
    public function pricingBand()
    {
        return $this->belongsTo(\App\Models\PricingBand::class, 'pricing_band_id');
    }

    /**
     * The ticket transaction that belong to the booked ticket.
     */
    public function ticketTransaction()
    {
        return $this->belongsTo(\App\Models\TicketTransaction::class, 'ticket_transaction_id');
    }
}
