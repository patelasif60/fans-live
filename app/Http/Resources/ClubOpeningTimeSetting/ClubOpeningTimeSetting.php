<?php

namespace App\Http\Resources\ClubOpeningTimeSetting;

use Illuminate\Http\Resources\Json\JsonResource;

class ClubOpeningTimeSetting extends JsonResource
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
            'food_and_drink_minutes_open_before_kickoff' => $this->food_and_drink_minutes_open_before_kickoff,
            'food_and_drink_minutes_closed_after_fulltime' => $this->food_and_drink_minutes_closed_after_fulltime,
            'merchandise_minutes_open_before_kickoff' => $this->merchandise_minutes_open_before_kickoff,            
            'merchandise_minutes_closed_after_fulltime' => $this->merchandise_minutes_closed_after_fulltime,
            'loyalty_rewards_minutes_open_before_kickoff' => $this->loyalty_rewards_minutes_open_before_kickoff,
            'loyalty_rewards_minutes_closed_after_fulltime' => $this->loyalty_rewards_minutes_closed_after_fulltime,
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
