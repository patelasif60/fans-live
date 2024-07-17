<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FCMService;
use App\Models\HospitalitySuiteNotification;
use App\Http\Resources\HospitalitySuite\HospitalitySuite as HospitalitySuiteResource;
use App\Http\Resources\Match\MatchBrief as MatchBriefResource;
use Carbon\Carbon;
use App\Services\ConsumerService;

class SendHospitalitySuiteNotifications extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'hospitalitysuitenotification:send';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'This command will send hospitality suite notifications to consumer';

	/**
	 * Firebase instance
	 *
	 * @var object
	 */
	protected $fcmService;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(FCMService $fcmService, ConsumerService $consumerService)
	{
		parent::__construct();
		$this->fcmService = $fcmService;
		$this->consumerService = $consumerService;
	}

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle()
	{
		$dateTime = Carbon::now()->toDateTimeString();
		$hospitalitySuiteNotifications = HospitalitySuiteNotification::with(['match' => function ($q) use ($dateTime) {
			$q->whereRaw('kickoff_time >= STR_TO_DATE("' . $dateTime . '", "%Y-%m-%d %H:%i:%s")');
		}, 'hospitalitySuite'])->whereHas('match', function ($q) use ($dateTime) {
			$q->whereRaw('kickoff_time >= STR_TO_DATE("' . $dateTime . '", "%Y-%m-%d %H:%i:%s")');
		})->where('is_notified', '=', 0)->where('reason', '=', 'unavailable')->get();

		if ($hospitalitySuiteNotifications) {
			foreach ($hospitalitySuiteNotifications as $hospitalitySuiteNotification) {
				$consumerSetting = $hospitalitySuiteNotification->consumer->settings;
				if ($consumerSetting && ($consumerSetting['is_notification_enabled'] == 'true')) {
					$checkForMembershipPackageForMatch = $this->consumerService->checkForMembershipPackageForMatch($hospitalitySuiteNotification->consumer, $hospitalitySuiteNotification->match->hospitalityMembership);
					if ($checkForMembershipPackageForMatch && $checkForMembershipPackageForMatch['is_ticket_available']) {
						$user = $hospitalitySuiteNotification->consumer->user;
						if (!empty($user->device_token)) {
							$fcmResponse = $this->fcmService->send($user->device_token, $hospitalitySuiteNotification->hospitalitySuite->title, "Tickets  are now available for this event.", ["hospitality_suite_detail" => new HospitalitySuiteResource($hospitalitySuiteNotification->hospitalitySuite, $hospitalitySuiteNotification->match_id, $hospitalitySuiteNotification->consumer, 'console'), 'match_detail' => new MatchBriefResource($hospitalitySuiteNotification->match), "notification_type" => "hospitality_suite_notification"]);
							if ($fcmResponse['number_of_success'] == 1) {
								$hospitalitySuiteNotification->is_notified = 1;
								$hospitalitySuiteNotification->save();
							}
						}
					}
				}
			}
		}
	}
}
