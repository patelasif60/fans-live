<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use App\Models\PushNotification;

class PushNotificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('push_notifications')->delete();
        $pushNotifications = [
        	[
	    		'club_id'                            => 12,
	            'message'                            => 'Test',
	            'swipe_action_category'              => 'food_and_drink_category',
	            'swipe_action_item'                  => 2,
	            'publication_date'                   => Carbon::now()->format('Y-m-d H:i:s'),
	            'created_by'                         => 1,
	            'membership_package_id'				 => 1,
        	],
        	[
	    		'club_id'                            => 12,
	            'message'                            => 'Demo',
	            'swipe_action_category'              => 'lottery_screen',
	            'publication_date'                   => Carbon::now()->format('Y-m-d H:i:s'),
	            'created_by'                         => 1,
	            'membership_package_id'				 => 3,
        	]
        ];
        foreach ($pushNotifications as $index => $p) {
            $pushNotification = PushNotification::create(Arr::except($p, ['membership_package_id']));
        	$pushNotification->membershippackages()->attach([$p['membership_package_id']]);
        }
    }
}
