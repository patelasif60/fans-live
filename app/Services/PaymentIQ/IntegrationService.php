<?php

namespace App\Services\PaymentIQ;

use Illuminate\Support\Str;
use App\Models\ProductTransaction;
use App\Models\EventTransaction;
use App\Models\HospitalitySuiteTransaction;
use App\Models\TicketTransaction;
use App\Models\ConsumerMembershipPackage;
use App\Services\ConsumerService;
use App\Services\ProductService;
use App\Services\EventService;
use App\Services\HospitalitySuiteService;
use App\Services\MembershipPackageService;
use App\Services\TicketService;
use App\Services\MatchService;
use App\Services\ClubAppSettingService;


/**
 * Integration service class to handle payment IQ basic integrations.
 */
class IntegrationService
{
	/**
	 * Create a consumer service variable.
	 *
	 * @return void
	 */
	protected $consumerService;

	/**
	 * Create a new service instance.
	 *
	 * @param ConsumerRepository $repository
	 */
	public function __construct(ConsumerService $consumerService,ProductService $productService,EventService $eventService, HospitalitySuiteService $hospitalitySuiteService ,MembershipPackageService $membershipPackageService, TicketService $ticketService,MatchService $matchService, ClubAppSettingService $clubAppSettingService)
	{
		$this->consumerService          = $consumerService;
		$this->productService           = $productService;
		$this->eventService             = $eventService;
		$this->hospitalitySuiteService  = $hospitalitySuiteService;
		$this->membershipPackageService = $membershipPackageService;
		$this->ticketService            = $ticketService;
		$this->matchService             = $matchService;
		$this->clubAppSettingService 	= $clubAppSettingService;
	}

	/**
	 * Verify user.
	 *
	 * @param $request
	 *
	 * @return mixed
	 */
	public function verifyUser($request)
	{
		$consumer = $this->consumerService->getConsumerDetailWithClub($request['userId']);
		return [
			"userId" => $consumer->user_id,
			"success" => true,
			"firstName" => $consumer->first_name,
			"lastName" => $consumer->last_name,
			"email" => $consumer->email,
			"balance" => 0,
			"balanceCy" => $consumer->club->currency,
		];
	}

	/**
	 * Authorize.
	 *
	 * @param $request
	 *
	 * @return mixed
	 */
	public function authorize($request)
	{
		$consumer = $this->consumerService->getConsumerDetailWithClub($request['userId']);

		if($request['attributes']['payment_type'] == 'product')
		{
			$authorisePayment = $this->productService->authoriseProductPayment($request['attributes'], $consumer);
		}
		else if($request['attributes']['payment_type'] == 'event')
		{
			$authorisePayment = $this->eventService->authoriseEventTicketPayment($request['attributes']);
		}
		else if($request['attributes']['payment_type'] == 'hospitality')
		{
			$authorisePayment = $this->hospitalitySuiteService->authoriseHospitalityTicketPayment($request['attributes']);
		}
		else if($request['attributes']['payment_type'] == 'match')
		{
			$authorisePayment = $this->ticketService->authoriseMatchTicketPayment($request['userId'], $request['attributes'], $request['txAmount']);
		}
		else {
			$authorisePayment = $this->membershipPackageService->authoriseMembershipPackagePayment($request['attributes']);
		}

		if($authorisePayment['status'] == 'error')
		{
			return [
				"userId" => $consumer->user_id,
				"success" => false,
				"merchantTxId" => 111,
				"authCode" => Str::uuid(),
				"errCode" => $authorisePayment['code'],
				"errMsg" => $authorisePayment['message']
			];
		}
		$transactionReferenceId = $this->createTransaction($request['attributes']['payment_type'], $consumer, $request);

		\Log::info(json_encode([
			"userId" => $consumer->user_id,
			"success" => true,
			"merchantTxId" => $transactionReferenceId,
			"authCode" => Str::uuid(),
		]));

		\Log::info("Authorize" . $transactionReferenceId);
		return [
			"userId" => $consumer->user_id,
			"success" => true,
			"merchantTxId" => $transactionReferenceId,
			"authCode" => Str::uuid(),
		];
	}

	/**
	 * Transfer.
	 *
	 * @param $request
	 *
	 * @return mixed
	 */
	public function transfer($request)
	{
		return [
			"userId" => $request['userId'],
			"success" => true,
			"txId" => $request['txId'],
			"merchantTxId" => $request['attributes']['merchantTxId'],
		];
	}

	/**
	 * Create transaction.
	 *
	 * @param $request
	 *
	 * @return mixed
	 */
	public function createTransaction($paymentType, $consumer, $request)
	{
		$data = $request;
		$data['cardDetails'] = [];
		$data['cardDetails']['maskedAccount'] = $request['maskedAccount'];
		$data['cardDetails']['accountId'] = $request['accountId'];
		$data['cardDetails']['accountHolder'] = $request['accountHolder'];

		$data['custom_parameters'] = [];
		$data['custom_parameters']['channelDetails'] = isset($request['channelDetails']) ? $request['channelDetails'] : null;
		$data['custom_parameters']['channelId'] = isset($request['channelId']) ? $request['channelId'] : null;
		$data['custom_parameters']['bootstrapVersion'] = isset($request['bootstrapVersion']) ? $request['bootstrapVersion'] : null;
		\Log::info('paymentType' . $paymentType);

		if($paymentType == 'product')
		{
			$clubTimings = $this->clubAppSettingService->getClubOpeningTimeSetting($consumer->club_id);
			$match = $this->matchService->getConsumerMatch($consumer, $data['attributes']['type'], $clubTimings);
			$createTransaction = $this->productService->createProductPurchase($consumer, $data, $request['txAmount'], $match);
			return $createTransaction->id;
		} else if($paymentType == 'event') {
			$eventData = $this->eventService->calculateEventTransactionPrice($request['attributes']);
			$createTransaction = $this->eventService->createEventPurchase($consumer, $data, $eventData);
			return $createTransaction->id;
		} else if($paymentType == 'hospitality') {
			$hospitalitySuiteData = $this->hospitalitySuiteService->calculateHospitalitySuiteTransactionPrice($request['attributes']);
			$createTransaction = $this->hospitalitySuiteService->createHospitalitySuitePurchase($consumer,$data,$hospitalitySuiteData);
			return $createTransaction->id;
		} else if($paymentType == 'match') {
			$createTransaction = $this->ticketService->createTicketPurchase($consumer, $data, $request['txAmount']);
			return $createTransaction->id;
		} else {
			$membershipPackage = $this->membershipPackageService->membershipPackageObj($data['attributes']['membership_package_id']);
			$createTransaction = $this->membershipPackageService->createConsumerMembershipPackagePurchase($membershipPackage, $consumer, $data);
			return $createTransaction->id;
		}
	}
	/**
	 * cancel .
	 *
	 * @param $request
	 *
	 * @return mixed
	 */
	public function cancel($request)
	{
		ProductTransaction::where('transaction_reference_id',$request['txId'])->count() > 0 ? $this->updateStatus('App\Models\ProductTransaction',$request['txId']) :'' ;
		EventTransaction::where('transaction_reference_id',$request['txId'])->count() > 0 ? $this->updateStatus('App\Models\EventTransaction',$request['txId']) :'' ;
		HospitalitySuiteTransaction::where('transaction_reference_id',$request['txId'])->count() > 0 ? $this->updateStatus('App\Models\HospitalitySuiteTransaction',$request['txId']) : '' ;
		TicketTransaction::where('transaction_reference_id',$request['txId'])->count() > 0 ? $this->updateStatus('App\Models\TicketTransaction',$request['txId']) :'' ;
		ConsumerMembershipPackage::where('transaction_reference_id',$request['txId'])->count() > 0 ? $this->updateStatus('App\Models\ConsumerMembershipPackage',$request['txId']) : '' ;

		return [
			"userId" => $request['userId'],
			"success" => true,
			"errCode" => $request['txId'],
			"errMsg" => 'Transaction Failed',
		];
	}
	/**
	*
	* update status
	*
	*/
	public function updateStatus($tableName,$transactionReferenceId)
	{
		return $tableName::where('transaction_reference_id', $transactionReferenceId)->update(['status' => 'failed']);
	}
}
