<?php

use Illuminate\Database\Seeder;
use App\Models\ClubLoyaltyPointSetting;
use App\Models\ClubModuleSetting;
use App\Models\ClubOpeningTimeSetting;
use App\Models\ClubTextSetting;
use Carbon\Carbon as Carbon;

class ClubAppSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('club_module_settings')->delete();
        DB::table('club_opening_time_settings')->delete();
        DB::table('club_loyalty_point_settings')->delete();
        DB::table('club_text_settings')->delete();
        ClubModuleSetting::insert([
            [
                'module_id' => 1,
                'is_active'   => 1,
                'club_id' => 12,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'module_id' => 2,
                'is_active'   => 1,
                'club_id' => 12,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'module_id' => 3,
                'is_active'   => 1,
                'club_id' => 12,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'module_id' => 4,
                'is_active'   => 1,
                'club_id' => 12,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'module_id' => 5,
                'is_active'   => 1,
                'club_id' => 12,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'module_id' => 6,
                'is_active'   => 1,
                'club_id' => 12,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'module_id' => 7,
                'is_active'   => 1,
                'club_id' => 12,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'module_id' => 8,
                'is_active'   => 1,
                'club_id' => 12,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'module_id' => 9,
                'is_active'   => 1,
                'club_id' => 12,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'module_id' => 10,
                'is_active'   => 1,
                'club_id' => 12,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'module_id' => 11,
                'is_active'   => 1,
                'club_id' => 12,
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'module_id' => 12,
                'is_active'   => 1,
                'club_id' => 12,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);
        ClubOpeningTimeSetting::insert([
        	[
                'club_id' => 12,
        		'food_and_drink_minutes_open_before_kickoff'=>50,
        		'food_and_drink_minutes_closed_after_fulltime'=>50,
        		'merchandise_minutes_open_before_kickoff'=>50,
        		'merchandise_minutes_closed_after_fulltime'=>50,
        		'loyalty_rewards_minutes_open_before_kickoff'=>50,
        		'loyalty_rewards_minutes_closed_after_fulltime'=>50,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        	]
        ]);
        ClubLoyaltyPointSetting::insert([
        	[
                'club_id' => 12,
        		'food_and_drink_reward_percentage'=>10,
        		'merchandise_reward_percentage'=>20,
        		'membership_packages_reward_percentage'=>30,
        		'hospitality_reward_percentage'=>20,
        		'events_reward_percentage'=>40,
                'tickets_reward_percentage'=>20,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        	]
        ]);
         ClubTextSetting::insert([
        	[
                'club_id' => 12,
        		'hospitality_post_purchase_text'=>'please purchase somthing',
        		'hospitality_introduction_text'=>'introduction',
        		'membership_packages_introduction_text'=>'membership introduction',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        	]
        ]);
    }
}
