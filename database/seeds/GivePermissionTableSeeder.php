<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class GivePermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::where('name', 'superadmin')->first();

        $superadminDashboard = Permission::create(['name' => 'access.superadmin.dashboard']);
        $role->givePermissionTo($superadminDashboard);

        $cms = Permission::create(['name' => 'manage.cms.users.all']);
        $role->givePermissionTo($cms);

        $staff = Permission::create(['name' => 'manage.staff.users.all']);
        $role->givePermissionTo($staff);

        $app = Permission::create(['name' => 'manage.app.users.all']);
        $role->givePermissionTo($app);

        $categories = Permission::create(['name' => 'manage.categories.all']);
        $role->givePermissionTo($categories);

        $clubs = Permission::create(['name' => 'manage.clubs.all']);
        $role->givePermissionTo($clubs);

        $competitions = Permission::create(['name' => 'manage.competitions.all']);
        $role->givePermissionTo($competitions);

        $clubadmin = Permission::create(['name' => 'access.clubadmin.dashboard.all']);
        $role->givePermissionTo($clubadmin);

        $settings = Permission::create(['name' => 'manage.app.settings.all']);
        $role->givePermissionTo($settings);

        $reports = Permission::create(['name' => 'generate.reports.all']);
        $role->givePermissionTo($reports);

        $stadium = Permission::create(['name' => 'manage.stadium.all']);
        $role->givePermissionTo($stadium);

        $feeds = Permission::create(['name' => 'manage.feeds.all']);
        $role->givePermissionTo($feeds);

        $news = Permission::create(['name' => 'manage.news.all']);
        $role->givePermissionTo($news);

        $ctas = Permission::create(['name' => 'manage.ctas.all']);
        $role->givePermissionTo($ctas);

        $polls = Permission::create(['name' => 'manage.polls.all']);
        $role->givePermissionTo($polls);

        $pushnotifications = Permission::create(['name' => 'manage.pushnotifications.all']);
        $role->givePermissionTo($pushnotifications);

        $travelinformation = Permission::create(['name' => 'manage.travelinformation.all']);
        $role->givePermissionTo($travelinformation);

        $membershippackages = Permission::create(['name' => 'manage.membershippackages.all']);
        $role->givePermissionTo($membershippackages);

        $events = Permission::create(['name' => 'manage.events.all']);
        $role->givePermissionTo($events);

        $matches = Permission::create(['name' => 'manage.matches.all']);
        $role->givePermissionTo($matches);

        $commerce = Permission::create(['name' => 'manage.commerce.all']);
        $role->givePermissionTo($commerce);

        $clubinformation = Permission::create(['name' => 'manage.clubinformation.all']);
        $role->givePermissionTo($clubinformation);

        $quizzes = Permission::create(['name' => 'manage.quizzes.all']);
        $role->givePermissionTo($quizzes);

        $videos = Permission::create(['name' => 'manage.videos.all']);
        $role->givePermissionTo($videos);

        $role = Role::where('name', 'clubadmin')->first();

        $clubAdminDashboard = Permission::create(['name' => 'access.clubadmin.dashboard.own']);
        $role->givePermissionTo($clubAdminDashboard);

        $appSettings = Permission::create(['name' => 'manage.app.settings.own']);
        $role->givePermissionTo($appSettings);

        $reports = Permission::create(['name' => 'generate.reports.own']);
        $role->givePermissionTo($reports);

        $manageStadium = Permission::create(['name' => 'manage.stadium.own']);
        $role->givePermissionTo($manageStadium);

        $manageFeeds = Permission::create(['name' => 'manage.feeds.own']);
        $role->givePermissionTo($manageFeeds);

        $manageNews = Permission::create(['name' => 'manage.news.own']);
        $role->givePermissionTo($manageNews);

        $manageCtas = Permission::create(['name' => 'manage.ctas.own']);
        $role->givePermissionTo($manageCtas);

        $managePolls = Permission::create(['name' => 'manage.polls.own']);
        $role->givePermissionTo($managePolls);

        $managePushnotifications = Permission::create(['name' => 'manage.pushnotifications.own']);
        $role->givePermissionTo($managePushnotifications);

        $manageTravelinformation = Permission::create(['name' => 'manage.travelinformation.own']);
        $role->givePermissionTo($manageTravelinformation);

        $manageMembershippackages = Permission::create(['name' => 'manage.membershippackages.own']);
        $role->givePermissionTo($manageMembershippackages);

        $manageEvents = Permission::create(['name' => 'manage.events.own']);
        $role->givePermissionTo($manageEvents);

        $manageMatches = Permission::create(['name' => 'manage.matches.own']);
        $role->givePermissionTo($manageMatches);

        $manageCommerce = Permission::create(['name' => 'manage.commerce.own']);
        $role->givePermissionTo($manageCommerce);

        $manageClubinformation = Permission::create(['name' => 'manage.clubinformation.own']);
        $role->givePermissionTo($manageClubinformation);

        $manageQuizzes = Permission::create(['name' => 'manage.quizzes.own']);
        $role->givePermissionTo($manageQuizzes);

        $manageVideos = Permission::create(['name' => 'manage.videos.own']);
        $role->givePermissionTo($manageVideos);

        $manageTransactions = Permission::create(['name' => 'access.clubadmin.transactions.own']);
        $role->givePermissionTo($manageTransactions);

        $manageUsers = Permission::create(['name' => 'access.clubadmin.users.own']);
        $role->givePermissionTo($manageUsers);
    }
}
