<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyRewardCollectionPoint extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'loyalty_reward_collection_points';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
}
