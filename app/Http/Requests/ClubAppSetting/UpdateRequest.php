<?php

namespace App\Http\Requests\ClubAppSetting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'food_and_drink_minutes_open_before_kickoff' => 'required',
            'food_and_drink_minutes_closed_after_fulltime' => 'required',
            'merchandise_minutes_open_before_kickoff' => 'required',
            'merchandise_minutes_closed_after_fulltime' => 'required',
            'loyalty_rewards_minutes_open_before_kickoff' => 'required',
            'loyalty_rewards_minutes_closed_after_fulltime' => 'required',
            'food_and_drink_reward_percentage' => 'required',
            'merchandise_reward_percentage' => 'required',
            'tickets_reward_percentage' => 'required',
            'membership_packages_reward_percentage' => 'required',
            'hospitality_reward_percentage' => 'required',
            'events_reward_percentage' => 'required',
            'hospitality_post_purchase_text' => 'required',
            'hospitality_introduction_text' => 'required',
            'membership_packages_introduction_text' => 'required',
        ];
    }
}
