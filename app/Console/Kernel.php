<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		\App\Console\Commands\GetCompetitionsFixtures::class,
		\App\Console\Commands\GetUpdateFeeds::class,
		\App\Console\Commands\SendPushNotifications::class,
		\App\Console\Commands\SendEventNotifications::class,
		\App\Console\Commands\SendMatchTicketNotifications::class,
		\App\Console\Commands\MakeUnsoldTicketsAsInactive::class,
		\App\Console\Commands\SendMembershipPackageExpiryNotification::class,
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param \Illuminate\Console\Scheduling\Schedule $schedule
	 *
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		$schedule->command('feed:update')->hourly();
		$schedule->command('footballapi:get-competitions-fixtures')->daily();
		$schedule->command('footballapi:update-match-details')->cron('*/2 2-23 * * *')->withoutOverlapping();
		$schedule->command('notification:send')->everyTenMinutes()->withoutOverlapping(); // Run every 10 min
		$schedule->command('make-unsold-ticket:inactive')->everyMinute()->withoutOverlapping(); // Run every 1 min
		$schedule->command('membership-package-expiry:notification')->daily(); // Run daily
	}

	/**
	 * Register the commands for the application.
	 *
	 * @return void
	 */
	protected function commands()
	{
		$this->load(__DIR__.'/Commands');

		require base_path('routes/console.php');
	}
}
