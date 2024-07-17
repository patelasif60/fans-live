<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTransaction extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_transactions';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The products that belong to the product transaction.
     */
    public function purchasedProducts()
    {
        return $this->hasMany(\App\Models\PurchasedProduct::class, 'product_transaction_id');
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
     * Get Product Transaction Collection status.
     */
    public function productTransactionCollection()
    {
        return $this->hasOne(\App\Models\ProductAndLoyaltyRewardTransactionCollection::class, 'transaction_id');
    }

    /**
     * Get consumer
     */
    public function consumer()
    {
        return $this->belongsTo(\App\Models\Consumer::class, 'consumer_id');
    }

    /**
     * Get club
     */
    public function club()
    {
        return $this->belongsTo(\App\Models\Club::class, 'club_id');
    }

    /**
     * Get Loyalty Reward Points
     */
    public function loyaltyRewardPoints()
    {
        return $this->hasOne(\App\Models\LoyaltyRewardPointHistory::class, 'transaction_id')
                            ->where(function ($query) {
                                $query->where('transaction_type', 'food_and_drink')
                                ->orWhere('transaction_type', 'merchandise');
                            });
    }

    /**
     * Get price.
     */
    public function getPriceAttribute()
    {
        return formatNumber($this->attributes['price']);
    }
}
