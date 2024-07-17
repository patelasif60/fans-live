<?php

use App\Models\MembershipPackage;
use Illuminate\Database\Seeder;

class MembershipPackagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('membership_packages')->delete();
        MembershipPackage::insert([
            [
                'club_id'                     => null,
                'title'                       => 'All fans',
                'benefits'                    => null,
                'membership_duration'         => null,
                'rewards_percentage_override' => null,
                'price'                       => 0,
                'vat_rate'                    => 0,
                'icon'                        => null,
                'icon_file_name'              => null,
                'status'                      => 'Published',
            ],
            [
                'club_id'                     => 10,
                'title'                       => 'Platinum',
                'benefits'                    => '<p>Our Platinum members play an influential role in the co‑operative sector. We have a long history of supporting the largest co-operatives in the UK, providing a tailored package of advice and support. Our Partners also have opportunities to play a more direct role in our campaigning and promotional work. Partner members enjoy the benefits of a dedicated account manager and a bespoke package of support.”</p>',
                'membership_duration'         => 5,
                'rewards_percentage_override' => 2,
                'price'                       => 35,
                'vat_rate'                    => 5,
                'icon'                        => 'http://placehold.it/50x50?text=Icon 2',
                'icon_file_name'              => 'icon2.png',
                'status'                      => 'Published',
            ],
            [
                'club_id'                     => 12,
                'title'                       => 'Platinum Plus',
                'benefits'                    => '<p>We understand that our members needs often go beyond our suite of online advice. Our expert advisers are here to help, whether that is bespoke advice on governance, membership, HR, legal issues or finance. For just £200 you can add on a package of support that gives you phone, email or face-to-face access to our expert advisers when you need it. Discover how the Contact package will benefit your co‑operative.”</p>',
                'membership_duration'         => 6,
                'rewards_percentage_override' => 5,
                'price'                       => 45,
                'vat_rate'                    => 5,
                'icon'                        => 'http://placehold.it/50x50?text=Icon 1',
                'icon_file_name'              => 'icon.png',
                'status'                      => 'Published',
            ],
        ]);
    }
}
