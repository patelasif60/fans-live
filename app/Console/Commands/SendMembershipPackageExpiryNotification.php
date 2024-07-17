<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ConsumerMembershipPackage;
use App\Services\FCMService;
use Carbon\Carbon;
use App\Http\Resources\ConsumerMembershipPackage\ConsumerMembershipPackage as ConsumerMembershipPackageResource;

class SendMembershipPackageExpiryNotification extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'membership-package-expiry:notification';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'This command will send membership package expiry notification.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(FCMService $fcmService)
	{
		parent::__construct();
		$this->fcmService = $fcmService;
	}

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle()
	{
		$currentDateTime = Carbon::now()->startOfDay();
		$consumerMembershipPackages = ConsumerMembershipPackage::where('is_active',1)->get();
		if ($consumerMembershipPackages) {
			foreach ($consumerMembershipPackages as $consumerMembershipPackage) {
				$membershipExpiryNotificationDateTime = Carbon::parse($consumerMembershipPackage->transaction_timestamp)->addMonths($consumerMembershipPackage->duration)->subDays(14)->startOfDay();
				if ($currentDateTime->eq($membershipExpiryNotificationDateTime)) {
					$user = $consumerMembershipPackage->consumer->user;
					if (!empty($user->device_token)) {
						$fcmResponse = $this->fcmService->send($user->device_token, 'Membership package', 'Dear customer, Your membership will expire within 14 days.', ['notification_type' => 'membership_package_expired']);
					}
				}
			}
		}
	}
}
