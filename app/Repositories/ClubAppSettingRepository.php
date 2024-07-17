<?php

namespace App\Repositories;

use App\Models\Club;
use App\Models\Module;
use App\Models\ClubTextSetting;
use App\Models\ClubModuleSetting;
use App\Models\ClubOpeningTimeSetting;
use App\Models\ClubLoyaltyPointSetting;

/**
 * Repository class for User model.
 */
class ClubAppSettingRepository extends BaseRepository
{
    public function getClubModuleSettings($clubId)
    {
        return ClubModuleSetting::where('club_id', $clubId);
    }

    public function createModuleEntry($user, $clubId, $moduleId, $isActive = 0)
    {
        $clubModuleSettings = new ClubModuleSetting();
        $clubModuleSettings->club_id = $clubId;
        $clubModuleSettings->module_id = $moduleId;
        $clubModuleSettings->is_active = $isActive;
        $clubModuleSettings->created_by = $user->id;
        $clubModuleSettings->save();
    }

    public function updateModuleEntry($clubModuleSettings, $isActive, $user)
    {
        $clubModuleSettings->update([
            'is_active' => $isActive,
            'updated_by'   => $user->id,
        ]);
    }

    public function getClubLoyaltyPointSetting($clubId)
    {
        return ClubLoyaltyPointSetting::where('club_id', $clubId)->first();
    }

    public function updateClubLoyaltyPointSetting($user, $clubLoyaltyPointSetting, $data)
    {
        $clubLoyaltyPointSetting->food_and_drink_reward_percentage = $data['food_and_drink_reward_percentage'];
        $clubLoyaltyPointSetting->merchandise_reward_percentage = $data['merchandise_reward_percentage'];
        $clubLoyaltyPointSetting->tickets_reward_percentage = $data['tickets_reward_percentage'];
        $clubLoyaltyPointSetting->membership_packages_reward_percentage = $data['membership_packages_reward_percentage'];
        $clubLoyaltyPointSetting->hospitality_reward_percentage = $data['hospitality_reward_percentage'];
        $clubLoyaltyPointSetting->events_reward_percentage = $data['events_reward_percentage'];
        $clubLoyaltyPointSetting->updated_by = $user->id;
        $clubLoyaltyPointSetting->save();
    }

    public function createClubLoyaltyPointSettingEntry($user, $clubId, $data)
    {
        $clubLoyaltyPointSetting = new ClubLoyaltyPointSetting();
        $clubLoyaltyPointSetting->club_id = $clubId;
        $clubLoyaltyPointSetting->food_and_drink_reward_percentage = $data['food_and_drink_reward_percentage'];
        $clubLoyaltyPointSetting->merchandise_reward_percentage = $data['merchandise_reward_percentage'];
        $clubLoyaltyPointSetting->tickets_reward_percentage = $data['tickets_reward_percentage'];
        $clubLoyaltyPointSetting->membership_packages_reward_percentage = $data['membership_packages_reward_percentage'];
        $clubLoyaltyPointSetting->hospitality_reward_percentage = $data['hospitality_reward_percentage'];
        $clubLoyaltyPointSetting->events_reward_percentage = $data['events_reward_percentage'];
        $clubLoyaltyPointSetting->created_by = $user->id;
        $clubLoyaltyPointSetting->save();
    }

    public function getClubTextSetting($clubId)
    {
        return ClubTextSetting::where('club_id', $clubId)->first();
    }

    public function updateClubTextSetting($user, $clubTextSetting, $data)
    {
        $clubTextSetting->hospitality_post_purchase_text = $data['hospitality_post_purchase_text'];
        $clubTextSetting->hospitality_introduction_text = $data['hospitality_introduction_text'];
        $clubTextSetting->membership_packages_introduction_text = $data['membership_packages_introduction_text'];
        $clubTextSetting->updated_by = $user->id;
        $clubTextSetting->save();
    }

    public function createClubTextSettingEntry($user, $clubId, $data)
    {
        $clubTextSetting = new ClubTextSetting();
        $clubTextSetting->club_id = $clubId;
        $clubTextSetting->hospitality_post_purchase_text = $data['hospitality_post_purchase_text'];
        $clubTextSetting->hospitality_introduction_text = $data['hospitality_introduction_text'];
        $clubTextSetting->membership_packages_introduction_text = $data['membership_packages_introduction_text'];
        $clubTextSetting->created_by = $user->id;
        $clubTextSetting->save();
    }

    public function getClubOpeningTimeSetting($clubId)
    {
        return ClubOpeningTimeSetting::where('club_id', $clubId)->first();
    }

    public function updateClubOpeningTimeSetting($user, $clubOpeningTimeSetting, $data)
    {
        $clubOpeningTimeSetting->food_and_drink_minutes_open_before_kickoff = $data['food_and_drink_minutes_open_before_kickoff'];
        $clubOpeningTimeSetting->food_and_drink_minutes_closed_after_fulltime = $data['food_and_drink_minutes_closed_after_fulltime'];
        $clubOpeningTimeSetting->merchandise_minutes_open_before_kickoff = $data['merchandise_minutes_open_before_kickoff'];
        $clubOpeningTimeSetting->merchandise_minutes_closed_after_fulltime = $data['merchandise_minutes_closed_after_fulltime'];
        $clubOpeningTimeSetting->loyalty_rewards_minutes_open_before_kickoff = $data['loyalty_rewards_minutes_open_before_kickoff'];
        $clubOpeningTimeSetting->loyalty_rewards_minutes_closed_after_fulltime = $data['loyalty_rewards_minutes_closed_after_fulltime'];
        $clubOpeningTimeSetting->updated_by = $user->id;
        $clubOpeningTimeSetting->save();
    }

    public function createClubOpeningTimeSettingEntry($user, $clubId, $data)
    {
        $clubOpeningTimeSetting = new ClubOpeningTimeSetting();
        $clubOpeningTimeSetting->club_id = $clubId;
        $clubOpeningTimeSetting->food_and_drink_minutes_open_before_kickoff = $data['food_and_drink_minutes_open_before_kickoff'];
        $clubOpeningTimeSetting->food_and_drink_minutes_closed_after_fulltime = $data['food_and_drink_minutes_closed_after_fulltime'];
        $clubOpeningTimeSetting->merchandise_minutes_open_before_kickoff = $data['merchandise_minutes_open_before_kickoff'];
        $clubOpeningTimeSetting->merchandise_minutes_closed_after_fulltime = $data['merchandise_minutes_closed_after_fulltime'];
        $clubOpeningTimeSetting->loyalty_rewards_minutes_open_before_kickoff = $data['loyalty_rewards_minutes_open_before_kickoff'];
        $clubOpeningTimeSetting->loyalty_rewards_minutes_closed_after_fulltime = $data['loyalty_rewards_minutes_closed_after_fulltime'];
        $clubOpeningTimeSetting->created_by = $user->id;
        $clubOpeningTimeSetting->save();
    }

    public function getAllModules()
    {
        return Module::all();
    }
}
