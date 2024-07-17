<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        $this->call(ClubCategoriesTableSeeder::class);
        $this->call(ClubsTableSeeder::class);
        $this->call(CompetitionsTableSeeder::class);
        $this->call(CompetitionClubTableSeeder::class);
        $this->call(MembershipPackagesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(PollsTableSeeder::class);
        $this->call(NewsTableSeeder::class);
        $this->call(GivePermissionTableSeeder::class);
        $this->call(TravelOffersTableSeeder::class);
        $this->call(TravelInformationPagesTableSeeder::class);
        $this->call(StadiumGeneralSettingsTableSeeder::class);
        $this->call(ContentFeedsTableSeeder::class);
        $this->call(ModulesTableSeeder::class);
        $this->call(EventsTableSeeder::class);
        $this->call(ClubAppSettingsTableSeeder::class);
        $this->call(ClubInformationPageTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
        $this->call(PushNotificationsTableSeeder::class);
        $this->call(VideosTableSeeder::class);
        $this->call(ClubBankDetailTableSeeder::class);
    }
}
