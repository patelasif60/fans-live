<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Loyalty Reward Point History class to handle operator interactions.
 */
class LoyaltyRewardPointHistory extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'loyalty_reward_point_histories';

	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id'];
}
