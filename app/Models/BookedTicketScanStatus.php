<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookedTicketScanStatus extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'booked_ticket_scan_status';

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
	 * Get the staff users.
	 */
	public function staff()
	{
		return $this->belongsTo(\App\Models\Staff::class, 'staff_id');
	}
}
