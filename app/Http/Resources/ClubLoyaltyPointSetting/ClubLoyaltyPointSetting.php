<?php

namespace App\Http\Resources\ClubLoyaltyPointSetting;

use Illuminate\Http\Resources\Json\JsonResource;

class ClubLoyaltyPointSetting extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'food_and_drink_reward_percentage' => $this->food_and_drink_reward_percentage,
            'merchandise_reward_percentage' => $this->merchandise_reward_percentage,
            'tickets_reward_percentage' => $this->tickets_reward_percentage,            
            'membership_packages_reward_percentage' => $this->membership_packages_reward_percentage,
            'hospitality_reward_percentage' => $this->hospitality_reward_percentage,
            'events_reward_percentage' => $this->events_reward_percentage,
            'created_by_first_name' => $this->creator->first_name,
            'created_by_last_name' => $this->creator->last_name,
            'created_by_email' => $this->creator->email,
            'created_by_id' => $this->creator->id,
            'updated_by_first_name' => $this->updater->first_name,
            'updated_by_last_name' => $this->updater->last_name,
            'updated_by_email' => $this->updater->email,
            'updated_by_id' => $this->updater->id,
        ];
    }
}
