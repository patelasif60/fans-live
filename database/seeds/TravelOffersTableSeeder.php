<?php

use App\Models\TravelOffer;
use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;

class TravelOffersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('travel_offers')->delete();
        TravelOffer::insert([
            [
                'club_id'             => 12,
                'title'               => 'SSC Napoli - Inter Milan',
                'content'             => '<p>The Premier League is the English and Welsh top domestic football division contested by 20 clubs and considered the world most attractive and evenly contested football league. The eight month season runs from August to May with teams playing 38 matches each.  The league was formed in 1992 and is the most televised and viewed domestic football competition in the world. Through the mass broadcast of fixtures, clubs such as Manchester United, Chelsea FC, Liverpool FC, Arsenal FC and Manchester City have become global brands and household names across all parts of the world.</p>',
                'thumbnail'           => 'http://placehold.it/50x50?text=Image',
                'thumbnail_file_name' => 'Thumbnail',
                'banner'              => 'http://placehold.it/500x500?text=Image',
                'banner_file_name'    => 'Banner',
                'icon'                => 'http://placehold.it/50x50?text=Image',
                'icon_file_name'      => 'Icon',
                'button_colour'       => '#000',
                'button_text_colour'  => '#fff',
                'button_text'         => 'Demo',
                'button_url'          => 'http://google.com',
                'status'              => 'Published',
                'publication_date'    => Carbon::now()->subDays(1)->format('Y-m-d H:i:s'),
                'show_until'          => Carbon::now()->addDays(2)->format('Y-m-d H:i:s'),
            ],
            [
                'club_id'             => 12,
                'title'               => 'AFC Ajax - PSV',
                'content'             => '<p>The eight month season runs from August to May with teams playing 38 matches each.  The league was formed in 1992 and is the most televised and viewed domestic football competition in the world. Through the mass broadcast of fixtures, clubs such as Manchester United, Chelsea FC, Liverpool FC, Arsenal FC and Manchester City have become global brands and household names across all parts of the world.</p>',
                'thumbnail'           => 'http://placehold.it/50x50?text=Image',
                'thumbnail_file_name' => 'Thumbnail',
                'banner'              => 'http://placehold.it/500x500?text=Image',
                'banner_file_name'    => 'Banner',
                'icon'                => 'http://placehold.it/50x50?text=Image',
                'icon_file_name'      => 'Icon',
                'button_colour'       => '#000',
                'button_text_colour'  => '#fff',
                'button_text'         => 'Demo 2',
                'button_url'          => 'http://google.com',
                'status'              => 'Published',
                'publication_date'    => Carbon::now()->subDays(1)->format('Y-m-d H:i:s'),
                'show_until'          => Carbon::now()->addDays(2)->format('Y-m-d H:i:s'),
            ],
        ]);
    }
}
