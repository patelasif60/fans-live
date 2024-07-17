<?php

namespace App\Services;

use App\Models\Consumer;
use App\Models\StadiumBlockSeat;
use App\Models\StadiumBlock;
use App\Models\TicketTransaction;
use App\Models\PricingBand;
use App\Models\BookedTicket;
use App\Models\Match;
use App\Models\SellMatchTicket;
use App\Models\StadiumGeneralSetting;
use App\Repositories\TicketRepository;
use App\Services\ACIWorldWide\Client;
use App\Services\FCMService;
use Carbon\Carbon;
use Illuminate\Http\Response;
use App\Services\ConsumerService;
use App\Http\Resources\TicketTransaction\TicketTransaction as TicketTransactionResource;
use App\Http\Resources\EventTransaction\EventTransaction as EventTransactionResource;
use App\Http\Resources\HospitalitySuiteTransaction\HospitalitySuiteTransaction as HospitalitySuiteTransactionResource;
use App\Http\Resources\BookedTicket\BookedTicket as BookedTicketResource;
use App\Http\Resources\BookedEvent\BookedEvent as BookedEventResource;
use App\Http\Resources\BookedHospitalitySuite\BookedHospitalitySuite as BookedHospitalitySuiteResource;
use Image;
use QrCode;

/**
 * User class to handle operator interactions.
 */
class TicketService
{
	/**
	 * The match repository instance.
	 *
	 * @var repository
	 */
	private $repository;

	/**
	 * Create a ACIWorldWide client API variable.
	 *
	 * @return void
	 */
	protected $apiclient;

	/**
	 * FCM service
	 *
	 * @var void
	 */
	protected $fcmService;

	/**
	 * Create a new service instance.
	 *
	 * @param TicketRepository $repository
	 */

	/**
     * Create a QRcode path  variable.
     *
     * @return void
     */
    protected $bookedTicketQrcodePath;

	public function __construct(TicketRepository $repository, Client $apiclient, FCMService $fcmService, ConsumerService $consumerService)
	{
		$this->repository   = $repository;
		$this->apiclient    = $apiclient;
		$this->fcmService   = $fcmService;
		$this->consumerService = $consumerService;
		$this->bookedTicketQrcodePath = config('fanslive.IMAGEPATH.booked_ticket_qrcode');
	}

	/**
	 * Destory/Unset object variables.
	 *
	 * @return void
	 */
	public function __destruct()
	{
		unset($this->repository);
		unset($this->apiclient);
	}

	/**
	 * Handle logic to save ticket notification.
	 *
	 * @param $user
	 * @param $data
	 *
	 * @return mixed
	 */
	public function saveTicketNotification($user, $data)
	{
		$consumer = Consumer::where('user_id', $user->id)->first();
		$data['consumer_id'] = $consumer->id;
		$data['club_id'] = $consumer->club_id;
		$ticketNotification = $this->repository->saveTicketNotification($data);
		if ($ticketNotification) {
			$message = "You will be notified if tickets become available.";
			if(isset($data['stadium_block_id']) && $data['stadium_block_id']) {
				$stadiumBlock = StadiumBlock::find($data['stadium_block_id']);
				$message = "You will be notified if tickets become available in block " . $stadiumBlock->name . ".";
			}
			return response()->json([
				'message' => 'Ticket notification has been saved successfully.',
			]);
		}
	}

	/**
	 * Prepare checkout
	 * Prepare checkout for ticket purchase.
	 *
	 * @param $consumerCard
	 *
	 * @return mixed
	 * @return \Illuminate\Http\Response
	 */
	public function prepareCheckoutForTicketPurchase($consumerCard, $currency, $totalPrice)
	{
		$errorFlag = false;
		return ['response' => ['id' => 1], 'errorFlag' => $errorFlag];

		$params = [
			'amount'              => $totalPrice,
			'currency'            => $currency,
			'shopperResultUrl'    => 'app://'.config('app.domain').'/url',
			'notificationUrl'     => config('app.url').'/api/registration_notification',
			'paymentType'         => 'DB',
			'registrations[0].id' => $consumerCard->token,
		];

		try {
			$response = $this->apiclient->createCheckout($params);
		} catch (\GuzzleHttp\Exception\ClientException $exception) {
			$errorFlag = true;
			$response = response()->json(['error' => 'Bad request'], 400);
		}

		return ['response' => $response, 'errorFlag' => $errorFlag];
	}

	/**
	 * Handle logic to create a ticket transactions details.
	 *
	 * @param $consumerCard
	 * @param $consumer
	 * @param $currency
	 *
	 * @return mixed
	 */
	public function createTicketPurchase($consumer, $data, $totalPrice)
	{
		$transactionData['match_id'] = $data['attributes']['match_id'];
		$transactionData['club_id'] = $consumer->club_id;
		$transactionData['consumer_id'] = $consumer->id;
		$transactionData['price'] = $totalPrice;
		$transactionData['currency'] = $consumer->club->currency;
		$transactionData['transaction_reference_id'] = $data['txId'];
		$transactionData['card_details'] = json_encode($data['cardDetails']);
		$transactionData['payment_status'] = 'Unpaid';
		$transactionData['custom_parameters'] = json_encode($data['custom_parameters']);
		$ticket = $this->repository->createTicketPurchase($transactionData);
		return $ticket;
	}

	/**
	 * Ticket payment
	 * Check payment status and response of ticket.
	 *
	 * @param $checkoutId
	 *
	 * @return mixed
	 * @return \Illuminate\Http\Response
	 */
	public function ticketPurchasePayment($checkoutId)
	{
		$errorFlag = false;
		return ['response' => ['id' => 1], 'errorFlag' => $errorFlag];

		try {
			$response = $this->apiclient->getPaymentStatus($checkoutId);
		} catch (\GuzzleHttp\Exception\ClientException $exception) {
			$errorFlag = true;
			$response = response()->json(['error' => 'Bad request'], 400);
		}

		return ['response' => $response, 'errorFlag' => $errorFlag];
	}

	/**
	 * Handle logic to update a membership package purchase details.
	 *
	 * @param $paymentResponse
	 * @param $ticketTransactionId
	 *
	 * @return mixed
	 */
	public function updateTicketPurchase($data,$totalPrice,$consumer)
	{
		$transactionData['status'] = $data['transaction_summary']['data']['status'];


		$transactionData['psp_reference_id'] = $data['transaction_summary']['data']['payload']['pspReferenceId'];

		$transactionData['payment_method'] = isset($data['transaction_summary']['data']['payload']['method']) ? $data['transaction_summary']['data']['payload']['method'] : null;

		$transactionData['status_code'] = isset($data['transaction_summary']['data']['payload']['status_code']) ? $data['transaction_summary']['data']['payload']['status_code'] : null;

		$transactionData['psp'] = $data['transaction_summary']['data']['payload']['psp'];

		$transactionData['psp_account'] = isset($data['transaction_summary']['data']['payload']['psp_account'] ) ? $data['transaction_summary']['data']['payload']['psp_account'] : null;
		$transactionData['transaction_timestamp'] =Carbon::now()->format('Y-m-d H:i:s');
		$ticketFlag = $this->repository->getTicketTransactionData($data['transaction_summary']['data']['payload']['txRefId']);
        if($ticketFlag)
        {
            $ticket = $this->repository->updateTicketPurchase($transactionData,$ticketFlag);
        }
        else
        {
            $ticket = $this->createTicketPurchase($consumer, $data, $totalPrice);
        }
		return $ticket;
	}

	/**
	 * Handle logic to update a receipt number of ticket.
	 *
	 * @param $consumer
	 * @param $ticketTransacton
	 *
	 * @return mixed
	 */
	public function updateReceiptNumberOfTicket($consumer, $ticketTransacton)
	{
		$ticketTransacton->receipt_number = '#M'.sprintf('%04s', $consumer->club_id).sprintf('%04s', $ticketTransacton->id);
		$ticketTransacton->save();
	}

	/**
	 * Handle logic to save booked tickets.
	 *
	 * @param $ticketTransactonId
	 * @param $data
	 *
	 * @return mixed
	 */
	public function saveBookedTickets($ticketTransactonId, $data, $isSeatingUnallocated)
	{
		$saveData['ticket_transaction_id'] = $ticketTransactonId;
		foreach (json_decode($data['tickets']) as $ticket) {
			if($isSeatingUnallocated) {
				$this->notifyConsumerIfTicketSold($ticket->stadium_block_seat_id);
			}
			$saveData['stadium_block_seat_id'] = $isSeatingUnallocated ? $ticket->stadium_block_seat_id : null;
			$saveData['seat'] = (!$isSeatingUnallocated) ? $ticket->seat : null;
			$saveData['pricing_band_id'] = $ticket->pricing_band_id;
			$saveData['price'] = $ticket->price;
			$saveData['vat_rate'] = $ticket->vat_rate;
			$bookedTickets = $this->repository->saveBookedTickets($saveData);
			$image = (string) Image::make(QrCode::format('png')->size(300)->generate(json_encode(['url' => 'scan_ticket', 'ticket_id' => $bookedTickets->id, 'type' => 'Match'])))->encode('data-url');
			$qrcodeImage = uploadQRCodeToS3($image, $this->bookedTicketQrcodePath,$bookedTickets->id);
        }

	}

	protected function notifyConsumerIfTicketSold($stadiumBlockSeatId)
	{
		$bookedTicket = BookedTicket::join('sell_match_ticket', 'booked_tickets.id', '=', 'sell_match_ticket.booked_ticket_id')->where('sell_match_ticket.is_active', 1)->where('sell_match_ticket.is_sold', 0)->where('booked_tickets.stadium_block_seat_id',$stadiumBlockSeatId)->first();
		\Log::info(json_encode($bookedTicket));
		if ($bookedTicket) {
			$deviceToken = $bookedTicket->ticketTransaction->consumer->user->device_token;
			\Log::info($deviceToken);
			if (!empty($deviceToken)) {
				$notificationReponse = $this->fcmService->send($deviceToken, 'Congratulations! Ticket sold.', 'Dear customer, Your ticket sold successfully.', ["notification_type" => "sell_ticket_notification"]);
				\Log::info(json_encode($notificationReponse));
				if ($notificationReponse['number_of_success']) {
					$sellMatchTicket = SellMatchTicket::find($bookedTicket->id);
					$sellMatchTicket->is_active = 0;
					$sellMatchTicket->is_sold = 1;
					$sellMatchTicket->save();
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Handle logic to get ticket transaction.
	 *
	 * @param $consumerId
	 *
	 * @return mixed
	 */
	public function getTicketTransaction($consumerId)
	{
		return $this->repository->getTicketTransaction($consumerId);
	}

	/**
	 * Handle logic to get event transaction.
	 *
	 * @param $consumerId
	 *
	 * @return mixed
	 */
	public function getEventTransaction($consumerId)
	{
		return $this->repository->getEventTransaction($consumerId);
	}

	/**
	 * Handle logic to get hospitality suite transaction.
	 *
	 * @param $consumerId
	 *
	 * @return mixed
	 */
	public function getHospitalitySuiteTransaction($consumerId)
	{
		return $this->repository->getHospitalitySuiteTransaction($consumerId);
	}

	/**
	 * Handle logic to get user ticket wallet details.
	 *
	 * @param $ticketTransactions
	 * @param $eventTransactions
	 * @param $hospitalityTransactions
	 *
	 * @return mixed
	 */
	public function getUserTicketWalletDetails($ticketTransactions, $eventTransactions, $hospitalityTransactions,$resaleTicketId=null)
	{
		$data = [];
		$data['upcoming'] = [];
		$data['past'] = [];

		// Match Transactions
		$matchUpcomingTransactions = [];
		$matchPastTransactions = [];

		foreach ($ticketTransactions as $key => $value)
		{

			$currentTime = Carbon::now();
			$bookedTickets = new TicketTransactionResource($value);
			if(isset($bookedTickets->bookedTickets)) {
				foreach ($bookedTickets->bookedTickets as $ticket)
				{

					if(!in_array($ticket->id,$resaleTicketId))
					{
						if(Carbon::parse($bookedTickets->match->kickoff_time)->greaterThan($currentTime))
						{
							$matchUpcomingTransactions[] = new BookedTicketResource($ticket, true);
						}
						else
						{
							$matchPastTransactions[] = new BookedTicketResource($ticket, true);
						}
					}
				}
			}
		}

		// Event Transactions
		$eventUpcomingTransactions = [];
		$eventPastTransactions = [];

		foreach ($eventTransactions as $key => $value)
		{
			$currentTime = Carbon::now();
			$bookedEvents = new EventTransactionResource($value);
			if(isset($bookedEvents->bookedEvents)) {
				foreach ($bookedEvents->bookedEvents as $event)
				{
					if(Carbon::parse($bookedEvents->event->date_time)->greaterThan($currentTime))
					{
						$eventUpcomingTransactions[] = new BookedEventResource($event, true);
					}
					else
					{
						$eventPastTransactions[] = new BookedEventResource($event, true);
					}
				}
			}
		}

		// Event Transactions
		$hospitalityUpcomingTransactions = [];
		$hospitalityPastTransactions = [];

		foreach ($hospitalityTransactions as $key => $value)
		{
			$currentTime = Carbon::now();
			$bookedHospitalitySuites = new HospitalitySuiteTransactionResource($value);
			if(isset($bookedHospitalitySuites->bookedHospitalitySuits)) {
				foreach ($bookedHospitalitySuites->bookedHospitalitySuits as $hospitalitySuite)
				{
					if(Carbon::parse($bookedHospitalitySuites->match->kickoff_time)->greaterThan($currentTime))
					{
						$hospitalityUpcomingTransactions[] = new BookedHospitalitySuiteResource($hospitalitySuite, true);
					}
					else
					{
						$hospitalityPastTransactions[] = new BookedHospitalitySuiteResource($hospitalitySuite, true);
					}
				}
			}
		}

		$upcomingTransactionCollection = collect([]);
		$dataUpcomingTransactionCollection = $upcomingTransactionCollection->merge($matchUpcomingTransactions)->merge($eventUpcomingTransactions)->merge($hospitalityUpcomingTransactions);

		$sorted = $this->sortTransactionData($dataUpcomingTransactionCollection);
		$upcomingData = $sorted->values()->toArray();
		$data['upcoming'] = array_values($upcomingData);

		$pastTransactionCollection = collect([]);
		$dataPastTransactionCollection = $pastTransactionCollection->merge($matchPastTransactions)->merge($eventPastTransactions)->merge($hospitalityPastTransactions);

		$sorted = $this->sortTransactionData($dataPastTransactionCollection);
		$pastData = $sorted->values()->toArray();
		$data['past'] = array_values($pastData);

		return $data;
	}

	/**
	 * Handle logic to sort transaction data.
	 *
	 * @param $transactionData
	 *
	 * @return mixed
	 */
	public function sortTransactionData($transactionData)
	{
		return $transactionData->sortByDesc(function ($obj, $key) {
			if(isset($obj->ticket_transaction_id)) {
				if(isset($obj->ticketTransaction->transaction_timestamp)) {
					return Carbon::parse($obj->ticketTransaction->transaction_timestamp)->unix();
				}
			} else if(isset($obj->event_transaction_id)) {
				if(isset($obj->eventTransaction->transaction_timestamp)) {
					return Carbon::parse($obj->eventTransaction->transaction_timestamp)->unix();
				}
			} else {
				if(isset($obj->hospitalitySuiteTransaction->transaction_timestamp)) {
					return Carbon::parse($obj->hospitalitySuiteTransaction->transaction_timestamp)->unix();
				}
			}
		});
	}

	/**
	 * Handle logic to get consumer's booked ticket.
	 *
	 * @param $match
	 * @param $consumerId
	 *
	 * @return mixed
	 */
	public function getConsumerBookedTickets($match, $consumerId)
	{
		//Booked tickets
		$ticketTransactions = $match->ticketTransactions->where('consumer_id', $consumerId);
		$bookedTickets = 0;
		foreach ($ticketTransactions as $key => $transaction) {
			$bookedTickets += $transaction->bookedTickets->count();
		}

		return $bookedTickets;
	}

	/**
	 * Handle logic to get match available tickets.
	 *
	 * @param $match
	 *
	 * @return mixed
	 */
	public function getMatchAvilableTickets($match)
	{
		//Total tickets
		$bookedTicketIds = [];
		$blockIds = $match->ticketing->availableBlocks->pluck('block_id');
		$totalTickets = StadiumBlockSeat::whereIn('stadium_block_id', $blockIds)
										->where('type', 'Seat')
										->count();

		//Booked tickets
		$ticketTransactions = $match->ticketTransactions;
		$bookedTickets = 0;
		foreach ($ticketTransactions as $key => $transaction) {
			$bookedTickets += $transaction->bookedTickets->count();
			foreach ($transaction->bookedTickets as $key => $ticket) {
				$bookedTicketIds[] = $ticket->stadium_block_seat_id;
			}
		}
		$sellTicket = SellMatchTicket::whereIn('booked_ticket_id', $bookedTicketIds)->where('is_sold', 0)->where('is_active', 1)->count();

		return $totalTickets - ($bookedTickets-$sellTicket);
	}

	/**
	 * Handle logic to assign ticket to user for automatic ticket allocation.
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function assignSeatToUser($data, $match, $bookedSeatCount)
	{
		$ticketJson = json_decode($data['tickets']);
		$totalTickets = $data['number_of_seats'];

		for($i=1; $i<= $data['number_of_seats']; $i++) {
			$ticketJson[$i-1]->seat = $bookedSeatCount + $i;
		}
		return $ticketJson;
	}

	/**
	 * Handle logic to calculated booked ticket price.
	 *
	 * @param $match
	 *
	 * @return mixed
	 */
	public function calculateTicketTransactionPrice($tickets)
	{
		$ticketJson = json_decode($tickets);
		$totalPrice = 0;
		$ticketIds = [];
		foreach ($ticketJson as $key => $ticket) {
			$pricingBand = PricingBand::find($ticket->pricing_band_id);
			$pricingBandVateRate = $pricingBand->vat_rate;
			$perTicketPrice = $pricingBand->price;

			$ticketJson[$key]->vat_rate = $pricingBandVateRate;
			$ticketJson[$key]->price = $perTicketPrice + (( $perTicketPrice * $pricingBandVateRate ) / 100);
			$totalPrice += $ticketJson[$key]->price;
		}
		return ['tickets' => $ticketJson, 'totalPrice' => $totalPrice];
	}

	public function sellMatchTicket($data)
	{
	   return $this->repository->sellMatchTicket($data);
	}

	/**
	 * Get sell match ticket by match id and stadium block id.
	 *
	 * @param $matchId
	 * @param $stadiumBlockId
	 *
	 * @return mixed
	 */
	public function getSellMatchTicket($matchId, $stadiumBlockId)
	{
		return $this->repository->getSellMatchTicket($matchId, $stadiumBlockId);
	}

	/**
	 * store new booked ticket scan status.
	 *
	 * @param $ticketId
	 * @param $staffId
	 * @param $type
	 *
	 * @return mixed
	 */
	public function createBookedTicketScanStatus($ticketId, $staffId, $type)
	{
		return $this->repository->createBookedTicketScanStatus($ticketId, $staffId, $type);
	}
	 /**
	* validate  match ticket Payment
	*/
	public function validateMatchTicketPayment($data, $consumer)
	{
		$stadiumGeneralSetting = StadiumGeneralSetting::where('club_id', $consumer->club_id)->first();

		//if $stadiumGeneralSetting = false then assign seats automatically
		if(!$stadiumGeneralSetting->is_using_allocated_seating) {
			$bookedSeatCount = $this->consumerService->getBookedicket($data['match_id']);
			if( ($stadiumGeneralSetting->number_of_seats - $bookedSeatCount) < $data['number_of_seats'] )
			{
				return response()->json([
					'message' => 'Ticket not available'
				], 400);
			}
		}
		else
		{
			$match = Match::find($data['match_id']);
			$matchAvilableTickets = $this->getMatchAvilableTickets($match);
			if($matchAvilableTickets < $data['number_of_seats'] )
			{
				return response()->json([
					'message' => 'Ticket not available'
				], 400);
			}
		}

		$ticketData = $this->calculateTicketTransactionPrice($data['tickets']);
		if ($ticketData['totalPrice'] != $data['final_amount']) {
			return response()->json([
				'message' => 'Something went wrong!'
			], 400);
		}

		return response()->json([
			'message' => 'Cart is validated successfully.',
		]);
	}
	/**
	* authorised  match ticket Payment
	*/
	public function authoriseMatchTicketPayment($userId, $data, $totalAmount)
	{
		$consumer = Consumer::where('user_id', $userId)->first();
		$stadiumGeneralSetting = StadiumGeneralSetting::where('club_id', $consumer->club_id)->first();

		//if $stadiumGeneralSetting = false then assign seats automatically
		if(!$stadiumGeneralSetting->is_using_allocated_seating) {
			$bookedSeatCount = $this->consumerService->getBookedicket($data['match_id']);
			if( ($stadiumGeneralSetting->number_of_seats - $bookedSeatCount) < $data['number_of_seats'] )
			{
				return [
					'message' => 'Ticket not available',
					'status' => 'error',
					'code' => 400,
				];
			}
		} else {
			$match = Match::find($data['match_id']);
			$matchAvilableTickets = $this->getMatchAvilableTickets($match);
			if($matchAvilableTickets < $data['number_of_seats'] )
			{
				return [
					'message' => 'Ticket not available',
					'status' => 'error',
					'code' => 400,
				];
			}
		}

		$pricingBandIds = explode(",", $data['pricing_band_id']);
		foreach($pricingBandIds as $index=>$pricingBand) {
			$data['tickets'][$index]['pricing_band_id'] = $pricingBand;
		}
		$data['tickets'] = json_encode($data['tickets']);
		\Log::info($data['tickets']);
		$ticketData = $this->calculateTicketTransactionPrice($data['tickets']);
		\Log::info($totalAmount);
		\Log::info($ticketData['totalPrice']);
		if ($ticketData['totalPrice'] != $totalAmount) {
			return [
				'message' => 'Something went wrong!',
				'status' => 'error',
				'code' => 400,
			];
		}

		\Log::info('success');

		return [
			'status' => 'success',
			'code' => 200,
		];
	}
	/**
	 * Handle logic upload QR code of booked events.
	 *
	 * @param $eventTransactonId
	 * @param $data
	 *
	 * @return mixed
	 */
	public function uploadQRcode()
	{
		$bookedMatchTickets = $this->repository->getAllBookedMatchTicket();
		foreach($bookedMatchTickets as $bookedMatchTicketsVal)
		{
			$image = (string) Image::make(QrCode::format('png')->size(300)->generate(json_encode(['url' => 'scan_ticket', 'ticket_id' => $bookedMatchTicketsVal->id, 'type' => 'Match'])))->encode('data-url');
			$qrcodeImage = uploadQRCodeToS3($image, $this->bookedTicketQrcodePath,$bookedMatchTicketsVal->id);
		}
	}
}
