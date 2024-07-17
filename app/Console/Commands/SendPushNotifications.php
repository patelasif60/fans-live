<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PushNotificationService;
use App\Services\FCMService;
use App\Models\PushNotification;
use App\Models\TicketTransaction;
use Carbon\Carbon;
use App\Models\Category;
use App\Http\Resources\Category\Category as CategoryResource;

class SendPushNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send push notifications to users';

    /**
     * Push notification service
     *
     * @var string
     */
    protected $pushNotificationService;

    /**
     * FCM service
     *
     * @var string
     */
    protected $fcmService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(PushNotificationService $pushNotificationService, FCMService $fcmService)
    {
        parent::__construct();
        $this->pushNotificationService = $pushNotificationService;
        $this->fcmService = $fcmService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $currentDateTime = Carbon::now();
        $dateTimeOne = $currentDateTime->toDateTimeString();
        $dateTimeTwo = $currentDateTime->subMinutes(10)->toDateTimeString();
        $pendingPushNotifications = PushNotification::where(['is_notification_sent' => FALSE])
                                                    ->whereRaw('publication_date > STR_TO_DATE("'.$dateTimeTwo.'", "%Y-%m-%d %H:%i:%s")')
                                                    ->whereRaw('publication_date <= STR_TO_DATE("'.$dateTimeOne.'", "%Y-%m-%d %H:%i:%s")')
                                                    ->get();
        if ($pendingPushNotifications) {
            foreach ($pendingPushNotifications as $pendingPushNotification) {
                $membershipPackageIds = $pendingPushNotification->membershippackages->pluck('id')->toArray();
                $ticketTransactions = TicketTransaction::where('match_id', '=', $pendingPushNotification->send_to_user_attending_this_match)->where('status', '=', config('fanslive.TRANSACTION_STATUS.success'))->get();
                if ($ticketTransactions) {
                    if (in_array(config('fanslive.ALL_FANS_MEMBERSHIP_PACKAGE_ID'), $membershipPackageIds)) {
                        $consumerWithDeviceTokens = $ticketTransactions->pluck('consumer.user.device_token','consumer_id')->toArray();
                        $deviceTokenArr = $ticketTransactions->pluck('consumer.user.device_token')->toArray();
                        $deviceTokenArr = array_filter($deviceTokenArr);
                    } else {
                        $consumerIds = $ticketTransactions->pluck('consumer_id');
                        $deviceTokenResp = $this->pushNotificationService->getDeviceTokensArr($consumerIds, $membershipPackageIds);
                        $consumerWithDeviceTokens = $deviceTokenResp['consumers_with_device_token'];
                        $deviceTokenArr = $deviceTokenResp['device_tokens'];
                    }
                    if ($deviceTokenArr) {
                        $fcmParams = [
                            'swipe_action_category' => $pendingPushNotification->swipe_action_category,
                            'swipe_action_item' => $pendingPushNotification->swipe_action_item,
                            'swipe_action_item_details'  => $pendingPushNotification->swipe_action_item !== null ? new CategoryResource(Category::find($pendingPushNotification->swipe_action_item)) : null,
                            "notification_type" => "cms_notification"
                        ];
                        $fcmResponseNumber = $this->fcmService->send($deviceTokenArr, $pendingPushNotification->title, $pendingPushNotification->message, $fcmParams);
                        $fcmResponseNumber['push_notification_id'] = $pendingPushNotification->id;

                        $pushNotificationHistory = $this->pushNotificationService->createHistory($fcmResponseNumber);

                        $failedTokens = [];
                        if (!empty($fcmResponseNumber['tokens_to_modify'])) {
                            $failedTokens = array_merge($failedTokens, array_keys($fcmResponseNumber['tokens_to_modify']));
                            $this->pushNotificationService->modifyDeviceToken($fcmResponseNumber['tokens_to_modify']);
                        }

                        if (!empty($fcmResponseNumber['tokens_to_delete'])) {
                            $failedTokens = array_merge($failedTokens, $fcmResponseNumber['tokens_to_delete']);
                            $this->pushNotificationService->removeDeviceToken($fcmResponseNumber['tokens_to_delete']);
                        }

                        if (!empty($fcmResponseNumber['tokens_to_retry'])) {
                            $failedTokens = array_merge($failedTokens, $fcmResponseNumber['tokens_to_retry']);
                        }

                        if (!empty($fcmResponseNumber['tokens_with_errors'])) {
                            $failedTokens = array_merge($failedTokens, array_keys($fcmResponseNumber['tokens_with_errors']));
                        }

                        $this->pushNotificationService->createHistoryConsumerStatus($pushNotificationHistory->id, $consumerWithDeviceTokens, $failedTokens);

                        sleep(1);

                        // $pendingPushNotification->is_notification_sent = 1;
                        // $pendingPushNotification->save();
                    }
                }
            }
        }
    }
}
