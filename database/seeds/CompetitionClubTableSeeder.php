<?php

use Illuminate\Database\Seeder;

class CompetitionClubTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('competition_club')->delete();
        DB::table('competition_club')->insert([
            [
                'competition_id'=> 1,
                'club_id'       => 1,
            ],
            [
                'competition_id'=> 1,
                'club_id'       => 2,
            ],
            [
                'competition_id'=> 1,
                'club_id'       => 3,
            ],
            [
                'competition_id'=> 1,
                'club_id'       => 4,
            ],
            [
                'competition_id'=> 1,
                'club_id'       => 5,
            ],
            [
                'competition_id'=> 2,
                'club_id'       => 6,
            ],
            [
                'competition_id'=> 2,
                'club_id'       => 7,
            ],
            [
                'competition_id'=> 2,
                'club_id'       => 8,
            ],
            [
                'competition_id'=> 2,
                'club_id'       => 9,
            ],
            [
                'competition_id'=> 1,
                'club_id'       => 10,
            ],
            [
                'competition_id'=> 4,
                'club_id'       => 11,
            ],
            [
                'competition_id'=> 4,
                'club_id'       => 12,
            ],
            [
                'competition_id'=> 4,
                'club_id'       => 13,
            ],
        ]);
    }
}
