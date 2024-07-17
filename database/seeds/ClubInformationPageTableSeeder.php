<?php

use Illuminate\Database\Seeder;
use App\Models\ClubInformationPage;
use App\Models\ClubInformationContentSections;
use Carbon\Carbon as Carbon;

class ClubInformationPageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('club_information_pages')->delete();
        $data = [
            [
                'club_id'          => 12,
                'title'            => 'Real Madrid - Celta de Vigo',
                'icon'             => 'http://placehold.it/10x10?text=Image',
                'icon_file_name'   => 'Icon',
                'status'           => 'Published',
                'publication_date' => Carbon::now()->subDays(1)->format('Y-m-d H:i:s'),
            ],
            [
                'club_id'          => 12,
                'title'            => 'Real Madrid - SD Eibar',
                'icon'             => 'http://placehold.it/10x10?text=Image',
                'icon_file_name'   => 'Icon',
                'status'           => 'Published',
                'publication_date' => Carbon::now()->subDays(1)->format('Y-m-d H:i:s'),
            ],
        ];
        foreach ($data as $key => $value) {
            $clubInformationPage = ClubInformationPage::create($value);
            if ($clubInformationPage) {
                ClubInformationContentSections::insert([
                    [
                        'club_information_page_id' => $clubInformationPage->id,
                        'title'                      => 'Estadio Santiago Bernabéu, Madrid',
                        'content'                    => 'A visit to a live home match of Real Madrid, the Royal Club, is one very special experience. The wonderful past players, the classic white strip, the awesome Santiago Bernabéu stadium... everything about this club is very special!',
                        'display_order'              => $key + 1,
                    ],
                ]);
            }
        }
    }
}
