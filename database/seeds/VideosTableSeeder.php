<?php

use Illuminate\Database\Seeder;
use App\Models\Video;
use Carbon\Carbon as Carbon;

class VideosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('videos')->delete();
		Video::insert([
			[
				'club_id'          => 12,
				'title'            => 'Spanish media on Real Madrid’s Champions League exit',
				'description'      => '<p>Real Madrid’s mourners filed out in silence while Ajax danced on their graves. As the north end of the Santiago Bernabéu roared to the sound of thousands of Dutch fans celebrating, the south stood abandoned and sad. Marca published an image of the north end as the final minutes ticked by, the Ajax section full, Madrid’s emptying, scoreboard quantifying their pain like some gigantic tombstone, on a front cover that was sparse and sombre. “Here lies the team that made history,” it said, adding: “A humiliating end to an unrepeatable era.”</p>',
				'image'            => 'http://placehold.it/500x500',
				'image_file_name'  => 'Image',
				'video'            => 'https://www.learningcontainer.com/wp-content/uploads/2020/05/sample-mp4-file.mp4',
				'video_file_name'  => 'sample-mp4-file',
				'status'           => 'Published',
				'publication_date' => Carbon::now()->subDays(1)->format('Y-m-d H:i:s'),
			],
			[
				'club_id'          => 12,
				'title'            => 'Brendan Rodgers describes burglary at family home as ‘horrendous’',
				'description'      => '<p>Brendan Rodgers has spoken of his family’s “horrendous” ordeal after their home in Scotland was broken into while they were sleeping. Police Scotland were called to the property in Bearsden, East Dunbartonshire, at around 1.55am on Wednesday. Rodgers’ wife and step-daughter, Charlotte and Lola, were in the house and a number of items were stolen, but no one was injured.</p>',
				'image'            => 'http://placehold.it/500x500',
				'image_file_name'  => 'Image',
				'video'            => 'https://www.learningcontainer.com/wp-content/uploads/2020/05/sample-mp4-file.mp4',
				'video_file_name'  => 'sample-mp4-file',
				'status'           => 'Published',
				'publication_date' => Carbon::now()->subDays(1)->format('Y-m-d H:i:s'),
			],
		]);
    }
}
