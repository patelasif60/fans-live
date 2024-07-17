<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ClubBankDetail extends model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'club_bank_details';

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

}
