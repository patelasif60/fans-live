<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EventNotification;
use App\Services\FCMService;
use App\Http\Resources\Event\Event as EventResource;
use Carbon\Carbon;

class SendEventNotifications extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'eventnotification:send';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'This command will send event notifications to consumer';

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
		$dateTime = Carbon::now()->toDateTimeString();
		$eventNotifications = EventNotification::with(['event' => function($q) use($dateTime) {
			$q->whereRaw('date_time >= STR_TO_DATE("'.$dateTime.'", "%Y-%m-%d %H:%i:%s")');
		}])->whereHas('event', function($q) use($dateTime) {
			$q->whereRaw('date_time >= STR_TO_DATE("'.$dateTime.'", "%Y-%m-%d %H:%i:%s")');
		})->where('is_notified','=',0)->where('reason','=','unavailable')->get();
		if ($eventNotifications) {
			foreach ($eventNotifications as $eventNotification) {
				$consumerSetting = $eventNotification->consumer->settings;
				if ($consumerSetting && ($consumerSetting['is_notification_enabled'] == 'true')) {
					if ($eventNotification->event->eventMembershipPackageAccessByConsumer($eventNotification->consumer))
					{
						$user = $eventNotification->consumer->user;
						if (!empty($user->device_token)) {
							$fcmResponse = $this->fcmService->send($user->device_token, $eventNotification->event->title, "Tickets are now available for this event.", ["event_detail" => new EventResource($eventNotification->event, $eventNotification->consumer, 'console'), "notification_type" => "event_notification"]);
							if ($fcmResponse['number_of_success'] == 1) {
								$eventNotification->is_notified = 1;
								$eventNotification->save();
							}
						}
					}
				}
			}
		}
	}
}
