<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ClubLoyaltyPointSetting\ClubLoyaltyPointSetting as ClubLoyaltyPointSettingResource;
use App\Http\Resources\ClubModuleSetting\ClubModuleSetting as ClubModuleSettingResource;
use App\Http\Resources\ClubOpeningTimeSetting\ClubOpeningTimeSetting as ClubOpeningTimeSettingResource;
use App\Http\Resources\ClubTextSetting\ClubTextSetting as ClubTextSettingResource;
use App\Models\Consumer;
use App\Models\ClubTextSetting;
use App\Models\ClubModuleSetting;
use App\Models\ClubOpeningTimeSetting;
use App\Models\ClubLoyaltyPointSetting;
use JWTAuth;

/**
 * @group Club app settings
 *
 * APIs for app settings.
 */
class ClubAppSettingController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Get club app settings
     * Get all published club app settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function getClubAppSettingData()
    {
        $user = JWTAuth::user();
        $consumer = Consumer::where('user_id', $user->id)->first();
        $clubInfoText = ClubTextSetting::where('club_id', $consumer->club_id)->first();
        $clubModules = ClubModuleSetting::where('club_id', $consumer->club_id)->get();
        $clubLoyaltyPoints = ClubLoyaltyPointSetting::where('club_id', $consumer->club_id)->first();
        $clubOpeningTime = ClubOpeningTimeSetting::where('club_id', $consumer->club_id)->first();

        return response()->json([
            'module_settings' => $clubModules->count() > 0 ? ClubModuleSettingResource::collection($clubModules) : null,
            'opening_times_settings' => isset($clubOpeningTime) ? new ClubOpeningTimeSettingResource($clubOpeningTime) : null,
            'loyalty_points_settings' => isset($clubLoyaltyPoints) ? new ClubLoyaltyPointSettingResource($clubLoyaltyPoints) : null,
            'text_settings' => isset($clubInfoText) ? new ClubTextSettingResource($clubInfoText) : null,
        ]);
    }
}
