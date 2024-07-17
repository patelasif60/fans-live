<?php

namespace App\Services;

use App\Repositories\EventRepository;
use App\Models\Consumer;
use App\Models\Event;
use App\Services\ACIWorldWide\Client;
use Carbon\Carbon;
use Storage;
use Image;
use QrCode;

/**
 * Event class to handle operator interactions.
 */
class EventService
{
	/**
	 * The event repository instance.
	 *
	 * @var repository
	 */
	protected $repository;

	/**
	 * The event image path.
	 *
	 * @var logoPath
	 */
	protected $logoPath;

	/**
	 * Create a new service instance.
	 *
	 * @param EventRepository $repository
	 */

	/**
	 * Create a ACIWorldWide client API variable.
	 *
	 * @return void
	 */
	protected $apiclient;


	/**
	 * Create a QRcode path  variable.
	 *
	 * @return void
	 */
	protected $bookedEventTicketQrcodePath;


	public function __construct(EventRepository $repository, Client $apiclient)
	{
		$this->repository = $repository;
		$this->logoPath = config('fanslive.IMAGEPATH.event_logo');
		$this->apiclient = $apiclient;
		$this->bookedEventTicketQrcodePath = config('fanslive.IMAGEPATH.booked_event_qrcode');
	}

	/**
	 * Get event data.
	 *
	 * @param $clubId
	 * @param $data
	 *
	 * @return mixed
	 */
	public function getData($clubId, $data)
	{
		$event = $this->repository->getData($clubId, $data);

		return $event;
	}

	/**
	 * Handle logic to create a event.
	 *
	 * @param $clubId
	 * @param $user
	 * @param $data
	 *
	 * @return mixed
	 */
	public function create($clubId, $user, $data)
	{
		if (isset($data['logo'])) {
			$logo = uploadImageToS3($data['logo'], $this->logoPath);
			$data['logo'] = $logo['url'];
			$data['logo_file_name'] = $logo['file_name'];
		} else {
			$data['logo'] = null;
			$data['logo_file_name'] = null;
		}
		$event = $this->repository->create($clubId, $user, $data);

		return $event;
	}

	/**
	 * Handle logic to update a given event.
	 *
	 * @param $user
	 * @param $event
	 * @param $data
	 *
	 * @return mixed
	 */
	public function update($user, $event, $data)
	{
		$disk = Storage::disk('s3');
		if (isset($data['logo'])) {
			$existingLogo = $this->logoPath . $event->image_file_name;
			$disk->delete($existingLogo);
			$logo = uploadImageToS3($data['logo'], $this->logoPath);
			$data['logo'] = $logo['url'];
			$data['logo_file_name'] = $logo['file_name'];
		} else {
			$data['logo'] = $event->image;
			$data['logo_file_name'] = $event->image_file_name;
		}
		$eventToUpdate = $this->repository->update($user, $event, $data);

		return $eventToUpdate;
	}

	/**
	 * Handle logic to delete a given logo file.
	 *
	 * @param $event
	 *
	 * @return mixed
	 */
	public function deleteLogo($event)
	{
		$disk = Storage::disk('s3');
		$logo = $this->logoPath . $event->image_file_name;

		return $disk->delete($logo);
	}

	/**
	 * Handle logic to get only event membership package.
	 *
	 * @param $event
	 *
	 * @return mixed
	 */
	public function getEventMembershipPackage($event)
	{
		$getEventMembershipPackage = $this->repository->getEventMembershipPackage($event);

		$eventPackages = [];
		foreach ($getEventMembershipPackage as $key => $eventPackage) {
			$eventPackages = array_column($eventPackage->EventMembershipPackageAvailability->toArray(), 'membership_package_id');
		}

		return $eventPackages;
	}

	/**
	 * Handle logic to save event notification.
	 *
	 * @param $user
	 * @param $data
	 *
	 * @return mixed
	 */
	public function saveEventNotification($user, $data)
	{
		$consumer = Consumer::where('user_id', $user->id)->first();
		$data['consumer_id'] = $consumer->id;
		$data['club_id'] = $consumer->club_id;
		$eventNotification = $this->repository->saveEventNotification($data);
		if ($eventNotification) {
			return response()->json([
				'message' => 'Event notification has been saved successfully.',
			]);
		}
	}

	/**
	 * unset class instance or public property.
	 */
	public function __destruct()
	{
		unset($this->repository);
		unset($this->logoPath);
		unset($this->apiclient);
	}

	/**
	 * Prepare checkout
	 * Prepare checkout for event purchase.
	 *
	 * @param $consumerCard
	 *
	 * @return mixed
	 * @return \Illuminate\Http\Response
	 */
	public function prepareCheckoutForEventPurchase($consumerCard, $currency,$eventData)
	{
		$errorFlag = false;
		return ['response' => ['id' => 1], 'errorFlag' => $errorFlag]; //to skip  For testing purpose

		$params = [
			'amount' => $eventData['totalPrice'],
			'currency' => $currency,
			'shopperResultUrl' => 'app://' . config('app.domain') . '/url',
			'notificationUrl' => config('app.url') . '/api/registration_notification',
			'paymentType' => 'DB',
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

	 public function calculateEventTransactionPrice($event)
    {
        $eventData = event::find($event['event_id']);
        $totalPrice = $event['number_of_seats'] * ($eventData->price + ($eventData->price * $eventData->vat_rate) /100);
		$data['price'] = $eventData->price;
		$data['per_quantity_price'] = ($eventData->price + ($eventData->price * $eventData->vat_rate) /100);
		$data['vat_rate'] = $eventData->vat_rate;
		$data['totalPrice'] = $totalPrice;
		return $data;
    }

	/**
	 * Handle logic to create a event transactions details.
	 * Handle logic to create a even
	 * @param $consumerCard
	 * @param $consumer
	 * @param $currency
	 *
	 * @return mixed
	 */
	public function createEventPurchase($consumer, $data,$eventData)
	{
		$transactionData['transaction_reference_id'] = $data['txId'];
		$transactionData['event_id'] = $data['attributes']['event_id'];
		$transactionData['club_id'] = $consumer->club_id;
		$transactionData['consumer_id'] = $consumer->id;
		$transactionData['price'] = $eventData['totalPrice'];
		$transactionData['currency'] = $consumer->club->currency;
		$transactionData['per_quantity_price'] = $eventData['per_quantity_price'];
		$transactionData['vat_rate'] = $eventData['vat_rate'];
		$transactionData['card_details'] = json_encode($data['cardDetails']);
        $transactionData['payment_status'] = 'Unpaid';
        $transactionData['custom_parameters'] = json_encode($data['custom_parameters']);
		$event = $this->repository->createEventPurchase($transactionData);
		return $event;
	}

	/**
	 * Handle logic to save booked events.
	 *
	 * @param $eventTransactonId
	 * @param $data
	 *
	 * @return mixed
	 */
	public function saveBookedEvents($eventTransactionId,$bookedSeatCount,$numberOfSeats)
	{
		for($i=1; $i<=$numberOfSeats; $i++) {
			$data['seat'] = $bookedSeatCount + $i;
			$data['event_transaction_id'] = $eventTransactionId;
			$bookedEventTickets = $this->repository->saveBookedEvents($data);
			$image = (string) Image::make(QrCode::format('png')->size(300)->generate(json_encode(['url' => 'scan_ticket', 'ticket_id' => $bookedEventTickets->id, 'type' => 'Event'])))->encode('data-url');
			$qrcodeImage = uploadQRCodeToS3($image, $this->bookedEventTicketQrcodePath,$bookedEventTickets->id);
		}
	}

	/**
	 * Handle logic to update a receipt number of event.
	 *
	 * @param $consumer
	 * @param $eventTransaction
	 *
	 * @return mixed
	 */
	public function updateReceiptNumberOfEvent($consumer, $eventTransaction)
	{
		$eventTransaction->receipt_number = '#E'.sprintf('%04s', $consumer->club_id).sprintf('%04s', $eventTransaction->id);
		$eventTransaction->save();
	}

	/**
	 * Event payment
	 * Check payment status and response of event.
	 *
	 * @param $checkoutId
	 *
	 * @return mixed
	 * @return \Illuminate\Http\Response
	 */
	public function eventPurchasePayment($checkoutId)
	{
		$errorFlag = false;
		return ['response' => ['id' => 1], 'errorFlag' => $errorFlag]; //to skip  For testing purpose

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
	 * @param $eventTransactionId
	 *
	 * @return mixed
	 */
	public function updateEventPurchase($data,$eventData,$consumer)
	{
		$transactionData['status'] = $data['transaction_summary']['data']['status'];
        $transactionData['psp_reference_id'] = $data['transaction_summary']['data']['payload']['pspReferenceId'];
        $transactionData['payment_method'] = isset($data['transaction_summary']['data']['payload']['method']) ? $data['transaction_summary']['data']['payload']['method'] : null;

        $transactionData['status_code'] = isset($data['transaction_summary']['data']['payload']['status_code']) ? $data['transaction_summary']['data']['payload']['status_code'] : null;

        $transactionData['psp'] = $data['transaction_summary']['data']['payload']['psp'];

        $transactionData['psp_account'] = isset($data['transaction_summary']['data']['payload']['psp_account'] ) ? $data['transaction_summary']['data']['payload']['psp_account'] : null;
        $transactionData['transaction_timestamp'] =Carbon::now()->format('Y-m-d H:i:s');
		
		$eventFlag = $this->repository->getEventTransactionData($data['transaction_summary']['data']['payload']['txRefId']);
        
        if($eventFlag)
        {
            $event = $this->repository->updateEventPurchase($transactionData,$eventFlag);
        }
        else
        {
            $event = $this->createEventPurchase($consumer,$data,$eventData);
        }

		return $event;
	}
	public function bookedSeatCount($eventId)
	{
		return $this->repository->bookedSeatCount($eventId);
	}
	/**
    * validate event ticket payment
    */
    public function validateEventTicketPayment($data)
    {
        $event = Event::find($data['event_id']);
		$bookedSeatCount = $event->getEventTickets($event);
        if(($event->number_of_tickets - $bookedSeatCount ) < $data['number_of_seats'] )
		{
			return response()->json([
				'message' => 'Ticket not available'
			], 400);
		}
		$eventData = $this->calculateEventTransactionPrice($data);
        if ($eventData['totalPrice'] != $data['final_amount']) {
            return response()->json([
                'message' => 'Something went wrong!'
            ], 400);
        }

        return response()->json([
        	'message' => 'Cart is validated successfully.',
        ]);
    }
    /**
    * authorised event ticket payment
    */
    public function authoriseEventTicketPayment($data)
    {
        $event = Event::find($data['event_id']);
		$bookedSeatCount = $event->getEventTickets($event);
        if(($event->number_of_tickets - $bookedSeatCount ) < $data['number_of_seats'] )
		{
			return [
				'message' => 'Ticket not available',
				'status' => 'error',
                'code' => 400,
			];
		}
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
		$bookedEventTickets = $this->repository->getAllBookedEvent();
		foreach($bookedEventTickets as $bookedEventTicketsVal)
		{
			$image = (string) Image::make(QrCode::format('png')->size(300)->generate(json_encode(['url' => 'scan_ticket', 'ticket_id' => $bookedEventTicketsVal->id, 'type' => 'Event'])))->encode('data-url');

			$qrcodeImage = uploadQRCodeToS3($image, $this->bookedEventTicketQrcodePath,$bookedEventTicketsVal->id);
		}
	}
}
