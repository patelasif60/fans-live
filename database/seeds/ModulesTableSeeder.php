<?php

use App\Models\Module;
use Illuminate\Database\Seeder;

class ModulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('modules')->delete();
        Module::insert([
            [
                'title' => 'Ticketing',
                'key'   => 'ticketing',
            ],
            [
                'title' => 'Food and drink',
                'key'   => 'food_and_drink',
            ],
            [
                'title' => 'Find your seat',
                'key'   => 'find_your_seat',
            ],
            [
                'title' => 'Directions',
                'key'   => 'directions',
            ],
            [
                'title' => 'Player of match voting',
                'key'   => 'player_of_match_voting',
            ],
            [
                'title' => 'Merchandise',
                'key'   => 'merchandise',
            ],
            [
                'title' => 'Hospitality',
                'key'   => 'hospitality',
            ],
            [
                'title' => 'Events',
                'key'   => 'events',
            ],
            [
                'title' => 'Videos',
                'key'   => 'videos',
            ],
            [
                'title' => 'Quizzes',
                'key'   => 'quizzes',
            ],
            // [
            //     'title' => 'Betting',
            //     'key'   => 'betting',
            // ],
            // [
            //     'title' => 'Lottery',
            //     'key'   => 'lottery',
            // ],
        ]);
    }
}
