<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchasedLoyaltyRewardProduct extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'purchased_loyalty_reward_products';

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
     * Get options of a purchased loyalty reward product.
     */
	public function loyaltyRewardProduct()
    {
        return $this->belongsTo(\App\Models\LoyaltyReward::class, 'loyalty_reward_id');
    }

	/**
     * Get options of a purchased product.
     */
    public function options()
    {
        return $this->belongsToMany(\App\Models\LoyaltyRewardOption::class, 'purchased_loyalty_reward_product_options', 'purchased_loyalty_reward_product_id', 'loyalty_reward_option_id');
    }
}
