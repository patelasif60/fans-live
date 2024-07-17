<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SellMatchTicket;
use App\Services\FCMService;
use Carbon\Carbon;

class MakeUnsoldTicketsAsInactive extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'make-unsold-ticket:inactive';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'This command will make unsold tickets inactive.';

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
		$sellMatchTickets = SellMatchTicket::where('is_active', 1)->where('is_sold', 0)->get();
		if ($sellMatchTickets) {
			foreach ($sellMatchTickets as $sellMatchTicket) {
				$match = $sellMatchTicket->bookedTicket->ticketTransaction->match;
				$currentDateTime = Carbon::now();
				$hoursToSubstract = str_replace('_hours_before', '', $sellMatchTicket->return_time_to_wallet);
				$matchTicketSellTime = Carbon::parse($match->kickoff_time)->subHours($hoursToSubstract);
				if ($currentDateTime->gte($matchTicketSellTime)) {
					$user = $sellMatchTicket->bookedTicket->ticketTransaction->consumer->user;
					if (!empty($user->device_token)) {
						$fcmResponse = $this->fcmService->send($user->device_token, "Oops! Unsold ticket.", "Oops! Your ticket not sold.", ['notification_type' => 'match_ticket_unsold_notification']);
					}
					$sellMatchTicket->is_active = FALSE;
					$sellMatchTicket->save();
				}
			}
		}
	}
}
