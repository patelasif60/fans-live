<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ProductAndLoyaltyRewardTransactionCollection extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_and_loyalty_reward_transaction_collections';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The products that belong to the product transaction.
     */
    public function ProductAndLoyaltyRewardTransactionCollection()
    {
        return $this->belongsTo(\App\Models\ProductTransaction::class, 'transaction_id');
    }

    /**
     * Get the staff users.
     */
    public function staff()
    {
        return $this->belongsTo(\App\Models\Staff::class, 'staff_id');
    }

	/**
	 * Disable timestamps.
	 *
	 * @var array
	 */
	public $timestamps = false;
}
