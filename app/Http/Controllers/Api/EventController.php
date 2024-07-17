<?php

namespace App\Http\Controllers\Api;

use App\Models\ClubLoyaltyPointSetting;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Event\PaymentRequest;
use App\Http\Requests\Api\Event\GetEventsRequest;
use App\Http\Requests\Api\Event\MakePaymentRequest;
use App\Http\Requests\Api\Event\ValidatePaymentRequest;
use App\Http\Resources\Event\Event as EventResource;
use App\Services\EventService;
use App\Services\LoyaltyRewardPointHistoryService;
use App\Http\Requests\Api\Event\SaveNotificationRequest;
use App\Models\EventTransaction;
use JWTAuth;
use App\Models\Consumer;
use App\Http\Resources\EventTransaction\EventTransaction as EventTransactionResource;
use Illuminate\Support\Str;
use Mail;
use App\Mail\SendEventTicketPDF;
use App\Http\Requests\Api\Event\EmailInPdfRequest;
use PDF;
use Carbon\Carbon;
use App\Models\Club;
use App\Jobs\SendEventTransactionEmail;
use App\Services\UserService;



/**
 * @group Events
 *
 * APIs for events.
 */
class EventController extends Controller
{
	/**
	 * Create a event service variable.
	 *
	 * @return void
	 */
	protected $service;
	protected $loyaltyRewardPointHistoryService;
	protected $userService;
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(EventService $service, LoyaltyRewardPointHistoryService $loyaltyRewardPointHistoryService,UserService $userService)
	{
		$this->service = $service;
		$this->loyaltyRewardPointHistoryService = $loyaltyRewardPointHistoryService;
        $this->userService = $userService;
	}


	/**
	 * Get events
	 * Get all events of a club.
	 *
	 * @bodyParam club_id int required An id of a club. Example: 1
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getEvents(GetEventsRequest $request)
	{
		$user = JWTAuth::user();
		$consumer = Consumer::where('user_id', $user->id)->first();
		$clubId = $consumer->club->id;
		$events = Event::where('club_id', $clubId)->where('status', 'Published')->where('date_time','>=', Carbon::now())->orderBy('date_time')->get();
		return EventResource::collection($events);
	}

	/**
	 * Save event notification
	 *
	 * @bodyParam event_id int required An id of a match. Example: 1
	 * @bodyParam reason enum required A reason of a notification. Example: unavailable
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function saveEventNotification(SaveNotificationRequest $request)
	{
		$user = JWTAuth::user();

		return $this->service->saveEventNotification($user, $request->all());
	}
	/**
     * Email event tickets in pdf
     * Email event tickets in pdf.
     *
     * @bodyParam transaction_id int required The id of ticket transaction. Example: 1
     * @bodyParam email string required An email id of a user. Example: abc@example.com
     *
     * @return \Illuminate\Http\Response
     */
    public function emailEventTicketsInPdf(EmailInPdfRequest $request)
    {
        $uuid = (String) Str::uuid();
        $eventTransaction = EventTransaction::find($request['transaction_id']);
        $bookedEventTickets = new EventTransactionResource($eventTransaction);
        $clubDetail = Club::find($eventTransaction->club_id);
        $eventTicketsPdf = PDF::loadView('pdf.event_ticket_detail', ['bookedEventTickets' => $bookedEventTickets, 'clubDetail' => $clubDetail]);
        $file = storage_path('event_ticket'.$uuid.'.pdf');
        $eventTicketsPdf->save($file);
        Mail::to($request['email'])->send(new SendEventTicketPDF($file));
        unlink($file);
        return response()->json([
            'message' => 'Email has been sent successfully.',
        ]);
    }
	/**
	 * Make event ticket payment
	 * Make event ticket payment
	 *
	 * @bodyParam event_id int required The id of event. Example: 1
	 * @bodyParam number_of_seats int required The Number of seat. Example: 1
	 *
	 * @return \Illuminate\Http\Response
	 */
    public function makeEventTicketPayment(MakePaymentRequest $request)
    {
		$user = JWTAuth::user();
		$consumer = Consumer::where('user_id', $user->id)->first();
		$data = $request->all();
		$event = Event::find($data['event_id']);
		$bookedSeatCount = $event->getEventTickets($event);
		$eventsRewardPercentage = null;

		if(($event->number_of_tickets - $bookedSeatCount ) < $data['number_of_seats'] )
		{
			return response()->json([
				'message' => 'Ticket not available'
			], 400);
		}
		$eventData = $this->service->calculateEventTransactionPrice($data);

		$eventTransaction = $this->service->updateEventPurchase($data,$eventData,$consumer);

		if($data['transaction_summary']['data']['status']=='failed')
		{
			return $eventTransaction;
		}
		$this->service->saveBookedEvents($eventTransaction->id, $bookedSeatCount,$data['number_of_seats']);

		$this->service->updateReceiptNumberOfEvent($consumer, $eventTransaction);

		if($event->rewards_percentage_override !== null && $event->rewards_percentage_override !== '') {
			$eventsRewardPercentage = $event->rewards_percentage_override;
		} else {
			$clubLoyaltyPointData = ClubLoyaltyPointSetting::where('club_id', $consumer->club_id)->first();
			$eventsRewardPercentage = $clubLoyaltyPointData->events_reward_percentage;
		}
		$eventPrice = $eventTransaction->price;
		$earnedLoyaltyPoints = $eventPrice * $eventsRewardPercentage;

		$loyaltyRewardPointHistory = $this->loyaltyRewardPointHistoryService->createLoyaltyRewardPointHistory($consumer, $eventTransaction->id, $earnedLoyaltyPoints, 'event');
		//$eventTransaction = EventTransaction::find(1);
        $clubAdmins = $this->userService->clubAdmin($consumer->club_id);
		$superAdmins = $this->userService->superAdmin();
        SendEventTransactionEmail::dispatch($eventTransaction,$consumer,$clubAdmins,$superAdmins)->onQueue(config('fanslive.TRANSACTION_EMAILS'));
		return new EventTransactionResource($eventTransaction);
    }

    /**
     * Validate event ticket Payment
     */
    public function validateEventTicketPayment(ValidatePaymentRequest $request)
    {
    	return $this->service->validateEventTicketPayment($request->all());
    }
}
