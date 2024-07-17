<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MatchTicketNotification;
use App\Services\FCMService;
use App\Services\StadiumBlockService;
use App\Services\TicketService;
use App\Models\MatchTicketingMembershipPackage;
use App\Http\Resources\Match\Match as MatchResource;
use Carbon\Carbon;
use DB;

class SendMatchTicketNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'matchnotification:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will send match ticket notifications to consumer';

    /**
     * Firebase instance
     *
     * @var object
     */
    protected $fcmService;

    /**
     * Stadium block service instance
     *
     * @var object
     */
    protected $stadiumBlockService;

    /**
     * Ticket service instance
     *
     * @var object
     */
    protected $ticketService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(FCMService $fcmService, StadiumBlockService $stadiumBlockService, TicketService $ticketService)
    {
        parent::__construct();
        $this->fcmService = $fcmService;
        $this->stadiumBlockService = $stadiumBlockService;
        $this->ticketService = $ticketService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $currentDateTime = Carbon::now();
        $dateTime = $currentDateTime->toDateTimeString();
        $matchTicketNotifications = MatchTicketNotification::with(['match' => function($q) use($dateTime) {
            $q->whereRaw('kickoff_time >= STR_TO_DATE("'.$dateTime.'", "%Y-%m-%d %H:%i:%s")');
        }])->whereHas('match', function($q) use($dateTime) {
            $q->whereRaw('kickoff_time >= STR_TO_DATE("'.$dateTime.'", "%Y-%m-%d %H:%i:%s")');
        })->where('is_notified','=',0)->get();
        if ($matchTicketNotifications) {
            foreach ($matchTicketNotifications as $matchTicketNotification) {
                $consumerSetting = $matchTicketNotification->consumer->settings;
                if ($consumerSetting && ($consumerSetting['is_notification_enabled'] == 'true')) {
                    $sendNotification = FALSE;
                    if ($matchTicketNotification->reason == 'unavailable') {
                        $consumerActiveMembershipPackage = $matchTicketNotification->consumer->getActiveMembershipPackage();
                        $consumerActiveMembershipPackageId = $consumerActiveMembershipPackage ? $consumerActiveMembershipPackage->id : config('fanslive.ALL_FANS_MEMBERSHIP_PACKAGE_ID');
                        $matchTicketingMembershipPackage = MatchTicketingMembershipPackage::where('match_id','=',$matchTicketNotification->match_id)
                            ->where(function($query) use($consumerActiveMembershipPackageId) {
                                return $query->where('membership_package_id', '=', $consumerActiveMembershipPackageId)->orWhere('membership_package_id', '=', config('fanslive.ALL_FANS_MEMBERSHIP_PACKAGE_ID'));
                            })->whereRaw('date <= STR_TO_DATE("'.$dateTime.'", "%Y-%m-%d %H:%i:%s")')->first();
                        if ($matchTicketingMembershipPackage) {
                            $sendNotification = TRUE;
                        }
                    } else if ($matchTicketNotification->reason == 'sold_out') {
                        $availableSeat = $this->stadiumBlockService->availableSeat($matchTicketNotification->match_id, $matchTicketNotification->match->homeTeam->id);
                        if ($availableSeat[$matchTicketNotification->stadium_block_id] == 0) {
                            $sellMatchTicket = $this->ticketService->getSellMatchTicket($matchTicketNotification->match_id, $matchTicketNotification->stadium_block_id);
                            if ($sellMatchTicket) {
                                $sendNotification = TRUE;
                            }
                        }
                    }
                    if ($sendNotification) {
                        $user = $matchTicketNotification->consumer->user;
                        if (!empty($user->device_token)) {
                            $title = $matchTicketNotification->match->homeTeam->name . ' VS ' . $matchTicketNotification->match->awayTeam->name;
                            $description = 'Tickets are now available for this match';
                            if ($matchTicketNotification->stadiumBlock) {
                                $description = 'Tickets are now available for this match in ' . $matchTicketNotification->stadiumBlock->name . ' block.';
                            }
                            $fcmResponse = $this->fcmService->send($user->device_token, $title, $description, ['match_detail' => new MatchResource($matchTicketNotification->match, $matchTicketNotification->consumer, 'console'), "notification_type" => "match_notification"]);
                            if ($fcmResponse['number_of_success'] == 1) {
                                $matchTicketNotification->is_notified = 1;
                                $matchTicketNotification->save();
                            }
                        }
                    }
                }
            }
        }
    }

}
