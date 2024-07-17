<?php

namespace App\Services;

use App\Repositories\HospitalitySuiteRepository;
use App\Services\ACIWorldWide\Client;
use App\Models\HospitalitySuite;
use App\Models\Consumer;
use Carbon\Carbon;
use File;
use Storage;
use App\Services\PaymentIQ\FrontService;
use Image;
use QrCode;

class HospitalitySuiteService
{
	/**
	 * The category repository instance.
	 *
	 * @var repository
	 */
	protected $repository;
	protected $logoPath;

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
	protected $bookedHospitalitySuiteTicketQrcodePath;


	/**
	 * Create a new service instance.
	 *
	 * @param HospitalitySuiteRepository $repository
	 */
	public function __construct(HospitalitySuiteRepository $repository, Client $apiclient,FrontService $frontService)
	{
		$this->repository = $repository;
		$this->imagePath = config('fanslive.IMAGEPATH.hospitality_suite_image');
		$this->seatingPlanPath = config('fanslive.IMAGEPATH.hospitality_suite_seating_plan');
		$this->apiclient = $apiclient;
		$this->frontService = $frontService;
		$this->bookedHospitalitySuiteTicketQrcodePath = config('fanslive.IMAGEPATH.booked_hospitality_suite_qrcode');

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

	public function getData($data, $clubId)
	{
		$HospitalitySuite = $this->repository->getData($data, $clubId);

		return $HospitalitySuite;
	}

	/**
	 * Handle logic to create a Hospitality Suite.
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function create($clubId, $user, $data)
	{
		if (isset($data['image'])) {
			$banner = uploadImageToS3($data['image'], $this->imagePath);
			$data['image'] = $banner['url'];
			$data['image_file_name'] = $banner['file_name'];
		} else {
			$data['image'] = null;
			$data['image_file_name'] = null;
		}
		// if (isset($data['seating_plan'])) {
		// 	$thumbbnail = uploadImageToS3($data['seating_plan'], $this->seatingPlanPath);
		// 	$data['seating_plan'] = $thumbbnail['url'];
		// 	$data['seating_plan_file_name'] = $thumbbnail['file_name'];
		// } else {
		// 	$data['seating_plan'] = null;
		// 	$data['seating_plan_file_name'] = null;
		// }
		$hospitalitySuites = $this->repository->create($clubId, $user, $data);

		return $hospitalitySuites;
	}

	/**
	 * Handle logic to update a given Hospitality Suite.
	 *
	 * @param $data
	 * @param $id
	 *
	 * @return mixed
	 */
	public function update($user, $hospitalitySuites, $data)
	{
		$disk = Storage::disk('s3');
		if (isset($data['image'])) {
			$existingLogo = $this->imagePath . $hospitalitySuites->image_file_name;
			$disk->delete($existingLogo);

			$banner = uploadImageToS3($data['image'], $this->imagePath);
			$data['image'] = $banner['url'];
			$data['image_file_name'] = $banner['file_name'];
		} else {
			$data['image'] = $hospitalitySuites->image;
			$data['image_file_name'] = $hospitalitySuites->image_file_name;
		}
		// if (isset($data['seating_plan'])) {
		// 	$existingLogo = $this->seatingPlanPath . $hospitalitySuites->seating_plan_file_name;
		// 	$disk->delete($existingLogo);

		// 	$thumbbnail = uploadImageToS3($data['seating_plan'], $this->seatingPlanPath);
		// 	$data['seating_plan'] = $thumbbnail['url'];
		// 	$data['seating_plan_file_name'] = $thumbbnail['file_name'];
		// } else {
		// 	$data['seating_plan'] = $hospitalitySuites->seating_plan;
		// 	$data['seating_plan_file_name'] = $hospitalitySuites->seating_plan_file_name;
		// }

		$hospitalitySuitesToUpdate = $this->repository->update($user, $hospitalitySuites, $data);

		return $hospitalitySuitesToUpdate;
	}

	/**
	 * Handle logic to delete a given logo file.
	 *
	 * @param $data
	 * @param $id
	 *
	 * @return mixed
	 */
	public function deleteLogo($hospitalitySuites)
	{
		$disk = Storage::disk('s3');
		$image = $this->imagePath . $hospitalitySuites->image_file_name;
		$disk->delete($image);
		$seating_plan = $this->seatingPlanPath . $hospitalitySuites->seating_plan_file_name;

		return $disk->delete($seating_plan);
	}

	/**
	 * Prepare checkout
	 * Prepare checkout for hospitality suite purchase.
	 *
	 * @param $consumerCard
	 * @param $currency
	 *
	 * @return mixed
	 * @return \Illuminate\Http\Response
	 */
	public function prepareCheckoutForHospitalitySuitPurchase($data, $currency, $hospitalitySuiteData)
	{
		$errorFlag = false;
		$provider = 'creditcard';
		$type = 'deposit';
		$method = 'process';
		if(isset($data['is_new_card']) && $data['is_new_card']) {
			$cardParam = [
				'cardHolder' => $data['card_holder_name'],
				'encCreditcardNumber' => $this->frontService->encryptData($data['card_number']),
				'expiryMonth' => $data['expiry_month'],
				'expiryYear' => $data['expiry_year'],
				'encCvv' => $this->frontService->encryptData($data['cvv'])
			];
		} else {
			$cardParam = ['accountId'=> 'd751d5e0-ebf6-44a2-84c4-e437a55b2cfc'];
		}
		$params = [
		 	'amount' => $hospitalitySuiteData['totalPrice'],
		 	'currency' => $currency,
		 	'shopperResultUrl' => 'app://' . config('app.domain') . '/url',
		 	'notificationUrl' => config('app.url') . '/api/registration_notification',
		 	'paymentType' => 'DB',
		 	'registrations[0].id' => $consumerCard->token,
			'sessionId' => 1234567,
			'userId' => 'TEST_USER',
			'merchantId' => config('payment.paymentiq.merchant_id'),
		 ];

		$response = $this->frontService->processPayment($provider, $type, $method,array_merge($params,$cardParam));
		return ['response' => $response, 'errorFlag' => $errorFlag];
	}

	/**
	 * Handle logic to create a hospitality suite transactions details.
	 *
	 * @param $consumerCard
	 * @param $consumer
	 * @param $currency
	 *
	 * @return mixed
	 */
	public function createHospitalitySuitePurchase($consumer, $data, $hospitalitySuiteData)
	{
		$transactionData['transaction_reference_id'] = $data['txId'];
		$transactionData['hospitality_suite_id'] = $data['attributes']['hospitality_suits_id'];
		$transactionData['match_id'] = $data['attributes']['match_id'];
		$transactionData['club_id'] = $consumer->club_id;
		$transactionData['consumer_id'] = $consumer->id;
		$transactionData['price'] = $hospitalitySuiteData['totalPrice'];
		$transactionData['currency'] = $consumer->club->currency;
		$transactionData['per_quantity_price'] = $hospitalitySuiteData['per_quantity_price'];
		$transactionData['vat_rate'] = $hospitalitySuiteData['vat_rate'];
		$transactionData['card_details'] = json_encode($data['cardDetails']);
        $transactionData['payment_status'] = 'Unpaid';
        $transactionData['custom_parameters'] = json_encode($data['custom_parameters']);
		$hospitality = $this->repository->createHospitalitySuitePurchase($transactionData);

		return $hospitality;
	}


	/**
	 * Handle logic to save booked hospitality suite.
	 *
	 * @param $hospitalitySuiteTransactionId
	 * @param $data
	 *
	 * @return mixed
	 */
	public function saveBookedHospitalitySuite($hospitalitySuiteTransactionId, $data ,$bookedSeatCount)
	{
		for($i=1; $i<=$data['number_of_seats']; $i++) {
			$data['seat'] = $bookedSeatCount + $i;
			$data['hospitality_suite_transaction_id'] = $hospitalitySuiteTransactionId;
			$bookedHospitalitySuiteTickets = $this->repository->saveBookedHospitalitySuite($data);
			$image = (string) Image::make(QrCode::format('png')->size(300)->generate(json_encode(['url' => 'scan_ticket', 'ticket_id' => $bookedHospitalitySuiteTickets->id, 'type' => 'Hospitality'])))->encode('data-url');
			$qrcodeImage = uploadQRCodeToS3($image, $this->bookedHospitalitySuiteTicketQrcodePath,$bookedHospitalitySuiteTickets->id);
		}
	}

	/**
	 * Handle logic to update a receipt number of hospitality.
	 *
	 * @param $consumer
	 * @param $hospitalityTransaction
	 *
	 * @return mixed
	 */
	public function updateReceiptNumberOfHospitalitySuite($consumer, $hospitalityTransaction)
	{
		$hospitalityTransaction->receipt_number = '#H' . sprintf('%04s', $consumer->club_id) . sprintf('%04s', $hospitalityTransaction->id);
		$hospitalityTransaction->save();
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
	public function hospitalitySuitePurchasePayment($checkoutId)
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
	 * Handle logic to update a hospitality suite purchase details.
	 *
	 * @param $paymentResponse
	 * @param $hospitalitySuiteTransactionId
	 *
	 * @return mixed
	 */
	public function updateHospitalitySuitePurchase($data, $hospitalitySuiteData, $consumer)
	{
		$transactionData['status'] = $data['transaction_summary']['data']['status'];
        $transactionData['psp_reference_id'] = $data['transaction_summary']['data']['payload']['pspReferenceId'];
        $transactionData['payment_method'] = isset($data['transaction_summary']['data']['payload']['method']) ? $data['transaction_summary']['data']['payload']['method'] : null;

        $transactionData['status_code'] = isset($data['transaction_summary']['data']['payload']['status_code']) ? $data['transaction_summary']['data']['payload']['status_code'] : null;

        $transactionData['psp'] = $data['transaction_summary']['data']['payload']['psp'];

        $transactionData['psp_account'] = isset($data['transaction_summary']['data']['payload']['psp_account'] ) ? $data['transaction_summary']['data']['payload']['psp_account'] : null;
        $transactionData['transaction_timestamp'] =Carbon::now()->format('Y-m-d H:i:s');

		$hospitalitySuiteFlag = $this->repository->getHospitalitySuiteTransactionData($data['transaction_summary']['data']['payload']['txRefId']);
        if($hospitalitySuiteFlag)
        {
            $hospitalitySuite = $this->repository->updateHospitalitySuitePurchase($transactionData, $hospitalitySuiteFlag);
        }
        else
        {
            $hospitalitySuite = $this->createHospitalitySuitePurchase($consumer, $data, $hospitalitySuiteData);
        }
		return $hospitalitySuite;
	}
	public function calculateHospitalitySuiteTransactionPrice($hospitalitySuite)
    {
        $hospitalitySuiteData = HospitalitySuite::find($hospitalitySuite['hospitality_suits_id']);
        $data['totalPrice'] = $hospitalitySuite['number_of_seats'] * ($hospitalitySuiteData->price + ($hospitalitySuiteData->price * $hospitalitySuiteData->vat_rate) /100);
		$data['price'] = $hospitalitySuiteData->price;
		$data['per_quantity_price'] = ($hospitalitySuiteData->price + ($hospitalitySuiteData->price * $hospitalitySuiteData->vat_rate) /100);
		$data['vat_rate'] = $hospitalitySuiteData->vat_rate;
		return $data;
    }
    /**
	 * Handle logic to save hospitality suite dietary option.
	 *
	 * @param $hospitalitySuiteTransactionId
	 * @param $hospitalitySuitsObj
	 *
	 * @return mixed
	 */
    public function saveBookedHospitalitySuiteDietaryOption($hospitalitySuiteTransactionId, $hospitalitySuitsObj)
    {
    	$this->repository->saveBookedHospitalitySuiteDietaryOption($hospitalitySuiteTransactionId, $hospitalitySuitsObj);
    }

	/**
	 * Handle logic to get hospitality suite by match id.
	 *
	 * @param $matchId
	 *
	 * @return mixed
	 */
    public function getHospitalitySuiteByMatchId($matchId)
	{
		return  $this->repository->getHospitalitySuiteByMatchId($matchId);
	}

	/**
	 * Handle logic to save hospitality suite notification.
	 *
	 * @param $user
	 * @param $data
	 *
	 * @return mixed
	 */
	public function saveHospitalitySuiteNotification($user, $data)
	{
		$consumer = Consumer::where('user_id', $user->id)->first();
		$data['consumer_id'] = $consumer->id;
		$data['club_id'] = $consumer->club_id;
		$hospitalitySuiteNotification = $this->repository->saveHospitalitySuiteNotification($data);
		if ($hospitalitySuiteNotification) {
			return response()->json([
				'message' => 'Hospitality suite notification has been saved successfully.',
			]);
		}
	}
	/**
    * validate hospitalitysuite ticket payment
    */
    public function validateHospitalityTicketPayment($data)
    {
        $hospitalitySuite = HospitalitySuite::find($data['hospitality_suits_id']);
		$bookedSeatCount = $hospitalitySuite->getHospitalitySuiteTickets($hospitalitySuite,$data['match_id']);
        if(($hospitalitySuite->number_of_seat - $bookedSeatCount ) < $data['number_of_seats'] )
		{
			return response()->json([
				'message' => 'Ticket not available'
			], 400);
		}
		$hospitalitySuiteData = $this->calculateHospitalitySuiteTransactionPrice($data);
        if ($hospitalitySuiteData['totalPrice'] != $data['final_amount']) {
            return response()->json([
                'message' => 'Something went wrong!'
            ], 400);
        }

        return response()->json([
        	'message' => 'Cart is validated successfully.',
        ]);
    }
    /**
    * authorised hospitalitysuite ticket payment
    */
    public function authoriseHospitalityTicketPayment($data)
    {
        $hospitalitySuite = HospitalitySuite::find($data['hospitality_suits_id']);
		$bookedSeatCount = $hospitalitySuite->getHospitalitySuiteTickets($hospitalitySuite, $data['match_id']);
		\Log::info('hospitalitySuite_number_of_seat' . $hospitalitySuite->number_of_seat);
		\Log::info('bookedSeatCount' . $bookedSeatCount);
        if(($hospitalitySuite->number_of_seat - $bookedSeatCount ) < $data['number_of_seats'] )
		{
			return [
				'message' => 'Ticket not available',
				'status' =>'error',
                'code'=>400,
			];
		}
		return [
            'status' =>'success',
            'code'=>200,
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
		$hospitalitySuiteTickets = $this->repository->getAllBookedHospitalitySuites();
		foreach($hospitalitySuiteTickets as $bookedHospitalitySuiteTickets)
		{
			$image = (string) Image::make(QrCode::format('png')->size(300)->generate(json_encode(['url' => 'scan_ticket', 'ticket_id' => $bookedHospitalitySuiteTickets->id, 'type' => 'Hospitality'])))->encode('data-url');
			$qrcodeImage = uploadQRCodeToS3($image, $this->bookedHospitalitySuiteTicketQrcodePath,$bookedHospitalitySuiteTickets->id);
		}
	}
}
