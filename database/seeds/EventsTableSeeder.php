<?php

use App\Models\Event;
use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('events')->delete();
        Event::insert([
            [
                'club_id'    => 12,
                'title'      => 'Warwick Charity Football Match',
                'location'   => 'Harlow Town Football Club',
                'description'=> 'Scroll below for all our LIVE listings. Please note that football will not be shown during match days at Wembley Stadium.',
                'date_time'  => Carbon::now()->format('Y-m-d H:i:s'),
                'price'      => 10,
                'vat_rate'   => 1,
                'status'     => 'Published',
				'image'           => 'https://via.placeholder.com/150x150',
				'image_file_name' => 'Image',

			],
            [
                'club_id'    => 12,
                'title'      => 'Introduction to Analytics for Professional Football - London',
                'description'=> 'The match is in aid of Street Child. Street Child work in some of the toughest areas around the world, believing that achieving universal basic education is the single greatest step that can be taken towards the elimination of global poverty and we couldn not agree more.',
                'location'   => 'Coin Street Neighbourhood Centre',
                'date_time'  => Carbon::now()->format('Y-m-d H:i:s'),
                'price'      => 228,
                'vat_rate'   => 1.5,
                'status'     => 'Published',
				'image'           => 'https://via.placeholder.com/150x150',
				'image_file_name' => 'Image',

            ],
        ]);
    }
}
