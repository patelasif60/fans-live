<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;

class CompetitionsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('competitions')->delete();
        DB::table('competitions')->insert([
            [
                'name'            => 'FIFA World Cup',
                'logo'            => null,
                'logo_file_name'  => null,
                'external_app_id' => 2000,
                'status'          => 'Published',
                'is_primary'      => 1,
                'created_at'      => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'            => 'Bundesliga',
                'logo'            => null,
                'logo_file_name'  => null,
                'external_app_id' => 2002,
                'status'          => 'Published',
                'is_primary'      => 1,
                'created_at'      => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'            => 'Eredivisie',
                'logo'            => null,
                'logo_file_name'  => null,
                'external_app_id' => 2003,
                'status'          => 'Published',
                'is_primary'      => 1,
                'created_at'      => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'            => 'UEFA Champions League',
                'logo'            => null,
                'logo_file_name'  => null,
                'external_app_id' => 2001,
                'status'          => 'Published',
                'is_primary'      => 1,
                'created_at'      => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'            => 'Série A',
                'logo'            => null,
                'logo_file_name'  => null,
                'external_app_id' => 2013,
                'status'          => 'Published',
                'is_primary'      => 1,
                'created_at'      => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'            => 'Primera Division',
                'logo'            => null,
                'logo_file_name'  => null,
                'external_app_id' => 2014,
                'status'          => 'Published',
                'is_primary'      => 1,
                'created_at'      => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'            => 'Ligue 1',
                'logo'            => null,
                'logo_file_name'  => null,
                'external_app_id' => 2015,
                'status'          => 'Published',
                'is_primary'      => 1,
                'created_at'      => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'            => 'Championship',
                'logo'            => null,
                'logo_file_name'  => null,
                'external_app_id' => 2016,
                'status'          => 'Published',
                'is_primary'      => 1,
                'created_at'      => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'            => 'Primeira Liga',
                'logo'            => null,
                'logo_file_name'  => null,
                'external_app_id' => 2017,
                'status'          => 'Published',
                'is_primary'      => 1,
                'created_at'      => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'            => 'European Championship',
                'logo'            => null,
                'logo_file_name'  => null,
                'external_app_id' => 2018,
                'status'          => 'Published',
                'is_primary'      => 1,
                'created_at'      => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'            => 'Serie A',
                'logo'            => null,
                'logo_file_name'  => null,
                'external_app_id' => 2019,
                'status'          => 'Published',
                'is_primary'      => 1,
                'created_at'      => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'            => 'Premier League',
                'logo'            => null,
                'logo_file_name'  => null,
                'external_app_id' => 2021,
                'status'          => 'Published',
                'is_primary'      => 1,
                'created_at'      => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        ]);
    }
}
