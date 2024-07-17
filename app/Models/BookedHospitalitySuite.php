<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookedHospitalitySuite extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'booked_hospitality_suites';

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
	 * The hospitality suite transaction that belong to the booked hospitality suites.
	 */
	public function hospitalitySuiteTransaction()
	{
		return $this->belongsTo(\App\Models\HospitalitySuiteTransaction::class, 'hospitality_suite_transaction_id');
	}
}
