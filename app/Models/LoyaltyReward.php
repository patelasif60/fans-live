<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyReward extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'loyalty_rewards';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get loyalty rewards option.
     */
    public function loyaltyRewardsOptions()
    {
        return $this->hasMany(LoyaltyRewardOption::class, 'loyalty_reward_id');
    }

    /**
     * Get loyalty rewards collection points.
     */
    public function loyaltyRewardsCollectionPoints()
    {
        return $this->hasMany(LoyaltyRewardCollectionPoint::class, 'loyalty_reward_id');
    }
}
