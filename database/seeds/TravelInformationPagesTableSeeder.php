<?php

use App\Models\TravelInformationPage;
use App\Models\TravelInformationPageContent;
use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;

class TravelInformationPagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('travel_information_pages')->delete();
        $data = [
            [
                'club_id'          => 12,
                'title'            => 'Real Madrid - Celta de Vigo',
                'photo'            => 'http://placehold.it/50x50?text=Image',
                'photo_file_name'  => 'Photo',
                'icon'             => 'http://placehold.it/10x10?text=Image',
                'icon_file_name'   => 'Icon',
                'status'           => 'Published',
                'publication_date' => Carbon::now()->subDays(1)->format('Y-m-d H:i:s'),
            ],
            [
                'club_id'          => 12,
                'title'            => 'Real Madrid - SD Eibar',
                'photo'            => 'http://placehold.it/50x50?text=Image',
                'photo_file_name'  => 'Photo',
                'icon'             => 'http://placehold.it/10x10?text=Image',
                'icon_file_name'   => 'Icon',
                'status'           => 'Published',
                'publication_date' => Carbon::now()->subDays(1)->format('Y-m-d H:i:s'),
            ],
        ];
        foreach ($data as $key => $value) {
            $travelInformationPage = TravelInformationPage::create($value);
            if ($travelInformationPage) {
                TravelInformationPageContent::insert([
                    [
                        'travel_information_page_id' => $travelInformationPage->id,
                        'title'                      => 'Estadio Santiago Bernabéu, Madrid',
                        'content'                    => 'A visit to a live home match of Real Madrid, the Royal Club, is one very special experience. The wonderful past players, the classic white strip, the awesome Santiago Bernabéu stadium... everything about this club is very special!',
                        'display_order'              => $key + 1,
                    ],
                ]);
            }
        }
    }
}
