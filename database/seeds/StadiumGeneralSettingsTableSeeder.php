<?php

use Illuminate\Database\Seeder;

class StadiumGeneralSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('stadium_general_settings')->delete();
        DB::table('stadium_general_settings')->insert([
            'club_id'   => 12,
            'name'      => 'Queen Elizabeth Olympic Park',
            'address'   => '1540 University Drive',
            'address_2' => 'London',
            'town'      => 'London',
            'postcode'  => 'E20 2ST',
            'latitude'  => '51.5387095',
            'longitude' => '-0.0166037',
            'aerial_view_ticketing_graphic' => 'https://fanslive-dev.s3.amazonaws.com/stadium_general_setting/stadium%20block_1554802260_1602234976.png'
        ]);
    }
}
