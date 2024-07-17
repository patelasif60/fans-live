<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;

class ClubCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('club_categories')->delete();
        DB::table('club_categories')->insert([
            [
                'name'           => 'Premier League',
                'logo'           => 'https://via.placeholder.com/150x150',
                'logo_file_name' => 'Image',
                'status'         => 'Published',
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'           => 'Série A',
                'logo'           => 'https://via.placeholder.com/150x150',
                'logo_file_name' => 'Image',
                'status'         => 'Published',
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'           => 'Primera Division',
                'logo'           => 'https://via.placeholder.com/150x150',
                'logo_file_name' => 'Image',
                'status'         => 'Published',
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'           => 'UEFA Champions League',
                'logo'           => 'https://via.placeholder.com/150x150',
                'logo_file_name' => 'Image',
                'status'         => 'Published',
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'           => 'Ligue 1',
                'logo'           => 'https://via.placeholder.com/150x150',
                'logo_file_name' => 'Image',
                'status'         => 'Published',
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'           => 'Championship',
                'logo'           => 'https://via.placeholder.com/150x150',
                'logo_file_name' => 'Image',
                'status'         => 'Published',
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'           => 'Primeira Liga',
                'logo'           => 'https://via.placeholder.com/150x150',
                'logo_file_name' => 'Image',
                'status'         => 'Published',
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'           => 'European Championship',
                'logo'           => 'https://via.placeholder.com/150x150',
                'logo_file_name' => 'Image',
                'status'         => 'Published',
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'           => 'Serie A',
                'logo'           => 'https://via.placeholder.com/150x150',
                'logo_file_name' => 'Image',
                'status'         => 'Published',
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        ]);
    }
}
