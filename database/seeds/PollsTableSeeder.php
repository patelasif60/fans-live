<?php

use App\Models\Poll;
use App\Models\PollOption;
use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;

class PollsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('polls')->delete();
        $pollOptions = ['Real Madrid', 'Celta de Vigo'];
        $data = [[
            'club_id'          => 12,
            'title'            => 'Real Madrid - Celta de Vigo',
            'question'         => 'Who will be the winner?',
            'status'           => 'Published',
            'publication_date' => Carbon::now()->subDays(1)->format('Y-m-d H:i:s'),
            'closing_date'     => Carbon::now()->addMonths(6),
            'display_results_date'     => Carbon::now()->addMonths(6),
            'created_by'       => 1,
        ],
            [
                'club_id'          => 12,
                'title'            => 'Real Madrid - Celta de Vigo',
                'question'         => 'Who will be the best team of the tournament?',
                'status'           => 'Hidden',
                'publication_date' => Carbon::now()->subDays(1)->format('Y-m-d H:i:s'),
                'closing_date'     => Carbon::now()->addMonths(8),
                'display_results_date'     => Carbon::now()->addMonths(8),
                'created_by'       => 1,
            ], ];
        foreach ($data as $key => $value) {
            $poll = Poll::create($value);
            if ($poll) {
                PollOption::insert([[
                    'poll_id' => $poll->id,
                    'text'    => $pollOptions[array_rand($pollOptions)],
                    'count'   => 0,
                ],
                    [
                        'poll_id' => $poll->id,
                        'text'    => $pollOptions[array_rand($pollOptions)],
                        'count'   => 0,
                    ],
                    [
                        'poll_id' => $poll->id,
                        'text'    => $pollOptions[array_rand($pollOptions)],
                        'count'   => 0,
                    ], ]);
            }
        }
    }
}
