<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class LoyaltyRewardTransaction extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'loyalty_reward_transactions';

	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id'];

	/**
     * The products that belong to the product transaction.
     */
    public function purchasedLoyaltyRewardProducts()
    {
        return $this->hasMany(\App\Models\PurchasedLoyaltyRewardProduct::class, 'loyalty_reward_transaction_id');
    }

    /**
     * The match that belong to the product transaction.
     */
    public function match()
    {
        return $this->belongsTo(\App\Models\Match::class, 'match_id');
    }

	/**
     * Get product collection point.
     */
    public function collectionPoint()
    {
        return $this->belongsTo(\App\Models\CollectionPoint::class, 'collection_point_id');
    }

    /**
     * Get Loyalty Reward Points
     */
    public function loyaltyRewardPoints()
    {
        return $this->hasOne(\App\Models\LoyaltyRewardPointHistory::class, 'transaction_id')->where('transaction_type', 'loyalty_reward');
    }

    /**
     * Get consumer
     */
    public function consumer()
    {
        return $this->belongsTo(\App\Models\Consumer::class, 'consumer_id');
    }

    /**
     * Get Loyalty Reward Transaction Collection status.
     */
    public function loyaltyRewardTransactionCollection()
    {
        return $this->hasOne(\App\Models\ProductAndLoyaltyRewardTransactionCollection::class, 'transaction_id');
    }
}
