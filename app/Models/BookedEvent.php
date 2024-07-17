<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookedEvent extends Model
{
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'booked_events';

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
	 * The event transaction that belong to the booked event.
	 */
	public function eventTransaction()
	{
		return $this->belongsTo(\App\Models\EventTransaction::class, 'event_transaction_id');
	}
}
