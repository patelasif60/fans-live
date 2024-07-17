<?php

namespace App\Http\Controllers\Api;

use App\Models\BookedTicketScanStatus;
use App\Models\ClubLoyaltyPointSetting;
use App\Services\LoyaltyRewardPointHistoryService;
use App\Services\StaffService;
use App\Services\ConsumerService;
use DB;
use Mail;
use App\Mail\SendMatchTicketPDF;
use App\Http\Requests\Api\Ticket\EmailInPdfRequest;
use App\Http\Requests\Api\Ticket\PaymentRequest;
use App\Http\Requests\Api\Ticket\MakePaymentRequest;
use App\Http\Requests\Api\Ticket\ValidatePaymentRequest;
use App\Http\Requests\Api\Ticket\SaveNotificationRequest;
use App\Http\Requests\Api\Ticket\ScanTicketRequest;
use App\Http\Requests\Api\Ticket\SellMatchTicketRequest;
use App\Http\Resources\TicketTransaction\TicketTransaction as TicketTransactionResource;
use App\Http\Resources\BookedTicket\BookedTicket as BookedTicketResource;
use App\Http\Resources\BookedTicket\BookedTicketCollection;
use App\Http\Resources\Match\Match as MatchResource;
use App\Http\Resources\Match\MatchBrief as MatchBriefResource;
use App\Models\BookedTicket;
use App\Models\BookedEvent;
use App\Models\BookedHospitalitySuite;
use App\Models\Consumer;
use App\Models\Match;
use App\Models\Club;
use App\Models\TicketTransaction;
use App\Models\EventTransaction;
use App\Models\HospitalitySuiteTransaction;
use App\Models\StadiumGeneralSetting;
use App\Models\SellMatchTicket;
use App\Services\TicketService;
use Illuminate\Support\Str;
use JWTAuth;
use PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Jobs\SendTicketTransactionEmail;
use App\Services\UserService;

/**
 * @group Ticket
 *
 * APIs for Ticket.
 */
class TicketController extends BaseController
{
	/**
	 * Create a match service variable.
	 *
	 * @return void
	 */
	protected $service;
	protected $loyaltyRewardPointHistoryService;
	protected $staffService;
    protected $userService;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(TicketService $service, LoyaltyRewardPointHistoryService $loyaltyRewardPointHistoryService, StaffService $staffService,ConsumerService $consumerService,UserService $userService)
	{
		$this->service = $service;
		$this->loyaltyRewardPointHistoryService = $loyaltyRewardPointHistoryService;
		$this->staffService = $staffService;
        $this->consumerService = $consumerService;
        $this->userService = $userService;
	}

	/**
	 * Save ticket notification
	 * Save ticket notification.
	 *
	 * @bodyParam match_id int required An id of a match. Example: 1
	 * @bodyParam reason enum required A reason of a notification. Example: unavailable
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function saveTicketNotification(SaveNotificationRequest $request)
	{
		$user = JWTAuth::user();

        return $this->service->saveTicketNotification($user, $request->all());
    }
    /**
     * Get user upcoming match ticket
     * Get user upcoming match ticket.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserUpcomingMatchTicket()
    {
        $bookedTickets = [];
        $tickets = [];
        $user = JWTAuth::user();
        $consumer = Consumer::where('user_id', $user->id)->first();
        $ticketTransactionMatchIds = TicketTransaction::where('club_id', $consumer->club_id)->where('consumer_id', $consumer->id)->pluck('match_id')->toArray();
        $match = Match::whereIn('id', $ticketTransactionMatchIds)->where('status', 'scheduled')->orderBy('kickoff_time', 'asc')->where(DB::raw('CONVERT(kickoff_time, date)'), '>=', Carbon::today())->first();

        if($match) {
            $tickets = TicketTransaction::where('match_id', $match->id)->where('consumer_id', $consumer->id)->get();
        }

        if($tickets) {
            $resaleTicketId = $this->resaleIds();
            $bookedTickets = $this->getBookedTickets($tickets->pluck('id'), $resaleTicketId);
        }
        return response()->json([
            'booked_tickets' => $bookedTickets ? BookedTicketCollection::make($bookedTickets)->checkWalletDetailsFlag(false) : $bookedTickets,
            'match' => $match ? new MatchBriefResource($match) : null
        ]);

    }

    /**
     * Email tickets in pdf
     * Email tickets in pdf.
     *
     * @bodyParam transaction_id int required The id of ticket transaction. Example: 1
     * @bodyParam email string required An email id of a user. Example: abc@example.com
     *
     * @return \Illuminate\Http\Response
     */
    public function emailMatchTicketsInPdf(EmailInPdfRequest $request)
    {
        $uuid = (String) Str::uuid();
        $ticketTransaction = TicketTransaction::find($request['ticket_transaction_id']);
        $bookedTickets = new TicketTransactionResource($ticketTransaction);
        $resaleTicketId=$this->resaleIds();
        $clubDetail = Club::find($ticketTransaction->club_id);
        $ticketsPdf = PDF::loadView('pdf.match_ticket_detail', ['bookedTickets' => $bookedTickets, 'resaleTicketId'=>$resaleTicketId, 'clubDetail' => $clubDetail]);
        $file = storage_path('match_ticket'.$uuid.'.pdf');
        $ticketsPdf->save($file);
        Mail::to($request['email'])->send(new SendMatchTicketPDF($file));
        unlink($file);
        return response()->json([
            'message' => 'Email has been sent successfully.',
        ]);
    }
    /**
     * Get user ticket wallet
     * Get user ticket wallet.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserTicketWalletDetails()
    {
        $user = JWTAuth::user();
        $consumer = Consumer::where('user_id', $user->id)->first();
        $resaleTicketId=$this->resaleIds();
        $ticketTransactions = $this->service->getTicketTransaction($consumer->id);
        $eventTransactions = $this->service->getEventTransaction($consumer->id);
        $hospitalityTransactions = $this->service->getHospitalitySuiteTransaction($consumer->id);

        return $this->service->getUserTicketWalletDetails($ticketTransactions, $eventTransactions, $hospitalityTransactions,$resaleTicketId);
    }
     /**
     * Sell match ticket
     *
     * Sell match ticket
.    *
     * @bodyParam booked_ticket_id int required An id of a booked ticket. Example: 1
     * @bodyParam return_time_to_wallet enum required A return time to wallet. Example: 72_hours_before
     * @bodyParam account_number string required An account no . Example: 14a
     * @bodyParam sort_code int required A short code. Example: 231d
     * @return \Illuminate\Http\Response
     */
    public function sellMatchTicket(SellMatchTicketRequest $request)
    {
        $user = JWTAuth::user();
        $consumer = Consumer::where('user_id', $user->id)->first();
        $sellMatchTicket = $this->service->sellMatchTicket($request->all());
        $hours = explode('_',$sellMatchTicket->return_time_to_wallet);
        return response()->json([
            'return_ticket_to_wallet_date_time' => Carbon::parse($sellMatchTicket->bookedTicket->ticketTransaction->match->kickoff_time)->subHours($hours['0'])->format('Y-m-d H:i:s')
        ]);
    }
     /**
     * Get available tickets.
     */
    public function getBookedTickets($tickets, $resaleTicketIds)
    {
        if($resaleTicketIds)
        {
            return BookedTicket::whereIn('ticket_transaction_id', $tickets)->whereNotIn('id', $resaleTicketIds)->get();
        }
        return BookedTicket::whereIn('ticket_transaction_id', $tickets)->get();
    }

	/**
	 * Scan booked ticket
	 *
	 * @bodyParam ticket_id int required An id of a booked ticket. Example: 1
	 * @return \Illuminate\Http\Response
	 */
	public function scanTicket(ScanTicketRequest $request)
	{
		$user = JWTAuth::user();
        $dateTime = Carbon::now()->toDateTimeString();
        if ($request->type == 'Event') {
            $bookedData = BookedEvent::with(['eventTransaction.event' => function($q) use($dateTime) {
                $q->whereRaw('date_time >= STR_TO_DATE("'.$dateTime.'", "%Y-%m-%d %H:%i:%s")');
            }])->whereHas('eventTransaction.event', function($q) use($dateTime) {
                $q->whereRaw('date_time >= STR_TO_DATE("'.$dateTime.'", "%Y-%m-%d %H:%i:%s")');
            })->where('id', $request->ticket_id)->first();
            if(!$bookedData) {
                return response()->json([
                    'message' => 'Sorry, Event not found'
                ], 400);
            }
        } else if ($request->type == 'Match') {
            $bookedData = BookedTicket::with(['ticketTransaction.match' => function($q) use($dateTime) {
                $q->whereRaw('kickoff_time >= STR_TO_DATE("'.$dateTime.'", "%Y-%m-%d %H:%i:%s")');
            }])->whereHas('ticketTransaction.match', function($q) use($dateTime) {
                $q->whereRaw('kickoff_time >= STR_TO_DATE("'.$dateTime.'", "%Y-%m-%d %H:%i:%s")');
            })->where('id', $request->ticket_id)->first();
            if(!$bookedData) {
                return response()->json([
                    'message' => 'Sorry, Match not found'
                ], 400);
            }
        } else if ($request->type == 'Hospitality') {
            $bookedData = BookedHospitalitySuite::with(['hospitalitySuiteTransaction.match' => function($q) use($dateTime) {
                $q->whereRaw('kickoff_time >= STR_TO_DATE("'.$dateTime.'", "%Y-%m-%d %H:%i:%s")');
            }])->whereHas('hospitalitySuiteTransaction.match', function($q) use($dateTime) {
                $q->whereRaw('kickoff_time >= STR_TO_DATE("'.$dateTime.'", "%Y-%m-%d %H:%i:%s")');
            })->where('id', $request->ticket_id)->first();
            if(!$bookedData) {
                return response()->json([
                    'message' => 'Sorry, Match not found'
                ], 400);
            }
        }
		$staff = $this->staffService->getStaffDetail($user->id);
        if (!$staff) {
            return response()->json([
                'message' => 'Sorry, Staff not found'
            ], 400);
        }
		if (empty($bookedData)) {
			return response()->json([
				'message' => 'Sorry, Ticket not found'
			], 400);
		} else {
			$bookedTicketScanStatus = BookedTicketScanStatus::where('ticket_id', $request->ticket_id)->where('type', $request->type)->first();
			if (empty($bookedTicketScanStatus)) {
				$bookedTicketScanStatusCreate = $this->service->createBookedTicketScanStatus($request->ticket_id, $staff->id, $request->type);
				return response()->json([
					'message' => 'The ticket was successfully scanned.'
				]);
			} else {
				$staffName = ucfirst($bookedTicketScanStatus->staff->user->first_name) . ' ' . ucfirst($bookedTicketScanStatus->staff->user->last_name);
				return response()->json([
					'message' => 'The ticket has already been scanned at ' . $bookedTicketScanStatus->scan_datetime . ' by staff member ' . $staffName . '.'
				], 400);
			}
		}
	}
    /**
     * Get user resale booked ticket ID.
     * Get user resale booked ticket ID.
     *
     * @return \Illuminate\Http\Response
     */
    public function resaleIds()
    {
        return SellMatchTicket::where(function ($q) {
                      $q->where('is_sold', 0)->where('is_active', 1);
            })
            ->orWhere(function ($q) {
                      $q->where('is_sold', 1)->where('is_active', 0);
            })->pluck('booked_ticket_id')->toArray();
    }
     /**
     * Make payment for match ticket
     * Make payment for match ticket
     *
     * @bodyParam tickets json required A tickets data. Example: [{"stadium_block_seat_id":451,"row":"Z","seat":"1","type":"seat","stadium_block_name":"W1H","block_id":1,"pricing_bands":[{"id":1,"price":2.3,"display_name":"Adult","is_selected":true}]},{"stadium_block_seat_id":452,"row":"Z","seat":"2","type":"seat","stadium_block_name":"W1H","block_id":1,"pricing_bands":[{"id":1,"price":2.3,"display_name":"Adult","is_selected":true}]}]
     *@bodyParam number_of_seats int required. Example: 1
     *@bodyParam match_id int required. The id of match. Example: 1
     *
     * @return \Illuminate\Http\Response
     */
     public function makeMatchTicketPayment(MakePaymentRequest $request)
     {
        $data = $request->all();
        $user = JWTAuth::user();
        $consumer = Consumer::where('user_id', $user->id)->first();
        $ticketsRewardPercentage = null;

        //Get total tickets
        $match = Match::find($data['match_id']);
        $stadiumGeneralSetting = StadiumGeneralSetting::where('club_id', $consumer->club_id)->first();

        //if $stadiumGeneralSetting = false then assign seats automatically
        if(!$stadiumGeneralSetting->is_using_allocated_seating) {
            $bookedSeatCount = $this->consumerService->getBookedicket($data['match_id']);
            if($stadiumGeneralSetting->number_of_seats - $bookedSeatCount < $data['number_of_seats'])
            {
                return response()->json([
	                'message' => 'Ticket not available'
	            ], 400);
            }
            $tickets = $this->service->assignSeatToUser($data, $match, $bookedSeatCount);
            $data['tickets'] = json_encode($tickets);
        }
        else
        {
            $matchAvilableTickets = $this->service->getMatchAvilableTickets($match);
            if($matchAvilableTickets < $data['number_of_seats'] )
            {
                return response()->json([
	                'message' => 'Ticket not available'
	            ], 400);
            }
        }
        $ticketData = $this->service->calculateTicketTransactionPrice($data['tickets']);

        if ($ticketData['totalPrice'] != $data['final_amount']) {
            return response()->json([
                'message' => 'Something went wrong!'
            ], 400);
        }

        $data['tickets'] = json_encode($ticketData['tickets']);
        \Log::info($data['tickets']);
        $totalPrice = $ticketData['totalPrice'];

        $ticketTransaction = $this->service->updateTicketPurchase($data,$totalPrice,$consumer);

        if($data['transaction_summary']['data']['status'] == 'failed') {
            return $ticketTransaction;
        }

        $this->service->saveBookedTickets($ticketTransaction->id, $data, $stadiumGeneralSetting->is_using_allocated_seating);

        $this->service->updateReceiptNumberOfTicket($consumer, $ticketTransaction);

        $ticketsRewardPrice = $ticketTransaction->price;
        if($match->ticketing->rewards_percentage_override !== null && $match->ticketing->rewards_percentage_override !== '') {
        	$ticketsRewardPercentage = $match->ticketing->rewards_percentage_override;
        } else {
        	$clubLoyaltyPointData = ClubLoyaltyPointSetting::where('club_id', $consumer->club_id)->first();
        	$ticketsRewardPercentage = $clubLoyaltyPointData->tickets_reward_percentage;
        }
        $earnedLoyaltyPoints = $ticketsRewardPrice * $ticketsRewardPercentage;

        $loyaltyRewardPointHistory = $this->loyaltyRewardPointHistoryService->createLoyaltyRewardPointHistory($consumer, $ticketTransaction->id, $earnedLoyaltyPoints, 'ticket');
        //$ticketTransaction =TicketTransaction::find(1);
        $clubAdmins = $this->userService->clubAdmin($consumer->club_id);
        $superAdmins = $this->userService->superAdmin();
        SendTicketTransactionEmail::dispatch($ticketTransaction,$consumer,$clubAdmins,$superAdmins)->onQueue(config('fanslive.TRANSACTION_EMAILS'));
        return new TicketTransactionResource($ticketTransaction);

    }

    /**
     * Validate match ticket Payment
     */
    public function validateMatchTicketPayment(ValidatePaymentRequest $request)
    {
    	$user = JWTAuth::user();
        $consumer = Consumer::where('user_id', $user->id)->first();
    	return $this->service->validateMatchTicketPayment($request->all(), $consumer);
    }
}
