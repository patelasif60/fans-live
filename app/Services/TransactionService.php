<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use App\Repositories\EventRepository;
use App\Repositories\TicketRepository;
use App\Repositories\HospitalitySuiteRepository;
use App\Repositories\ConsumerMembershipPackageRepository;
use App\Models\ProductTransaction;
use App\Models\EventTransaction;
use App\Models\TicketTransaction;
use App\Models\HospitalitySuiteTransaction;
use App\Models\ConsumerMembershipPackage;
use File;
use Storage;
use App\Services\PaymentIQ\FrontService;
/**
 * Product class to handle operator interactions.
 */
class TransactionService
{
    /**
     * The product repository instance.
     *
     * @var repository
     */
    protected $productRepository;

    /**
     * The event repository instance.
     *
     * @var repository
     */
    protected $eventRepository;

    /**
     * The ticket repository instance.
     *
     * @var repository
     */
    protected $ticketRepository;

    /**
     * The hospitality suite repository instance.
     *
     * @var repository
     */
    protected $hospitalitySuiteRepository;

    /**
     * The consumer membership package repository instance.
     *
     * @var repository
     */
    protected $consumerMembershipPackageRepository;

    /**
     * Create a new service instance.
     *
     * @param LoyaltyRewardRepository $repository
     */
    public function __construct(ProductRepository $productRepository, EventRepository $eventRepository, TicketRepository $ticketRepository, HospitalitySuiteRepository $hospitalitySuiteRepository, ConsumerMembershipPackageRepository $consumerMembershipPackageRepository,FrontService $frontService)
    {
        $this->productRepository = $productRepository;
        $this->eventRepository = $eventRepository;
        $this->ticketRepository = $ticketRepository;
        $this->hospitalitySuiteRepository = $hospitalitySuiteRepository;
        $this->consumerMembershipPackageRepository = $consumerMembershipPackageRepository;
        $this->frontService = $frontService;
    }

    /**
     * unset class instance or public property.
     */
    public function __destruct()
    {
        unset($this->eventRepository);
    }

    /**
     * Get transactions data.
     *
     * @param $clubId
     * @param $data
     *
     * @return mixed
     */
    public function getData($clubId, $data)
    {
        $productTransactionQuery = $this->productRepository->getProductTransactionQueryForTransactions();
        $eventTransactionQuery = $this->eventRepository->getEventTransactionQueryForTransactions();
        $ticketTransactionQuery = $this->ticketRepository->getTicketTransactionQueryForTransactions();
        $hospitalitySuiteQuery = $this->hospitalitySuiteRepository->getHospitalitySuiteTransactionQueryForTransactions();
        $consumerMembershipPackageQuery = $this->consumerMembershipPackageRepository->getConsumerMembershipPackageQueryForTransactions();

        if (isset($clubId) && trim($clubId) != '') {
            $productTransactionQuery->where('product_transactions.club_id', $clubId);
            $eventTransactionQuery->where('event_transactions.club_id', $clubId);
            $ticketTransactionQuery->where('ticket_transactions.club_id', $clubId);
            $hospitalitySuiteQuery->where('hospitality_suite_transactions.club_id', $clubId);
            $consumerMembershipPackageQuery->where('consumer_membership_package.club_id', $clubId);
        }

        if (isset($data['transaction_status']) && trim($data['transaction_status']) != '') {
            if ($data['transaction_status'] == 'successful') {
                $productTransactionQuery->where('product_transactions.status', 'successful');
                $eventTransactionQuery->where('event_transactions.status', 'successful');
                $ticketTransactionQuery->where('ticket_transactions.status', 'successful');
                $hospitalitySuiteQuery->where('hospitality_suite_transactions.status', 'successful');
                $consumerMembershipPackageQuery->where('consumer_membership_package.status', 'successful');
            } else if ($data['transaction_status'] == 'failed') {
                $productTransactionQuery->where('product_transactions.status', '=', 'failed');
                $eventTransactionQuery->where('event_transactions.status', '=', 'failed');
                $ticketTransactionQuery->where('ticket_transactions.status', '=', 'failed');
                $hospitalitySuiteQuery->where('hospitality_suite_transactions.status', '=', 'failed');
                $consumerMembershipPackageQuery->where('consumer_membership_package.status', '=', 'failed');
            }
        } else {
            $productTransactionQuery->where('product_transactions.status', '!=', 'unprocessed');
            $eventTransactionQuery->where('event_transactions.status', '!=', 'unprocessed');
            $ticketTransactionQuery->where('ticket_transactions.status', '!=', 'unprocessed');
            $hospitalitySuiteQuery->where('hospitality_suite_transactions.status', '!=', 'unprocessed');
            $consumerMembershipPackageQuery->where('consumer_membership_package.status', '!=', 'unprocessed');
        }

        if (isset($data['consumer_id']) && trim($data['consumer_id']) != '') {
            $productTransactionQuery->where('users.id', $data['consumer_id']);
            $eventTransactionQuery->where('users.id', $data['consumer_id']);
            $ticketTransactionQuery->where('users.id', $data['consumer_id']);
            $hospitalitySuiteQuery->where('users.id', $data['consumer_id']);
            $consumerMembershipPackageQuery->where('users.id', $data['consumer_id']);
        }

        if (isset($data['payment_brand']) && trim($data['payment_brand']) != '') {
            $productTransactionQuery->where('product_transactions.payment_brand', $data['payment_brand']);
            $eventTransactionQuery->where('event_transactions.payment_brand', $data['payment_brand']);
            $ticketTransactionQuery->where('ticket_transactions.payment_brand', $data['payment_brand']);
            $hospitalitySuiteQuery->where('hospitality_suite_transactions.payment_brand', $data['payment_brand']);
            $consumerMembershipPackageQuery->where('consumer_membership_package.payment_brand', $data['payment_brand']);
        }

        if (isset($data['payment_status']) && trim($data['payment_status']) != '') {
            $productTransactionQuery->where('product_transactions.payment_status', $data['payment_status']);
            $eventTransactionQuery->where('event_transactions.payment_status', $data['payment_status']);
            $ticketTransactionQuery->where('ticket_transactions.payment_status', $data['payment_status']);
            $hospitalitySuiteQuery->where('hospitality_suite_transactions.payment_status', $data['payment_status']);
            $consumerMembershipPackageQuery->where('consumer_membership_package.payment_status', $data['payment_status']);
        }

        if (isset($data['last_four_digit']) && trim($data['last_four_digit']) != '') {
            $productTransactionQuery->whereJsonContains('product_transactions.card_details->last4Digits', $data['last_four_digit']);
            $eventTransactionQuery->whereJsonContains('event_transactions.card_details->last4Digits', $data['last_four_digit']);
            $ticketTransactionQuery->where('ticket_transactions.card_details->last4Digits', $data['last_four_digit']);
            $hospitalitySuiteQuery->where('hospitality_suite_transactions.card_details->last4Digits', $data['last_four_digit']);
            $consumerMembershipPackageQuery->where('consumer_membership_package.card_details->last4Digits', $data['last_four_digit']);
        }

        if (isset($data['amount']) && trim($data['amount']) != '') {
            $productTransactionQuery->where('product_transactions.price', $data['amount']);
            $eventTransactionQuery->where('event_transactions.price', $data['amount']);
            $ticketTransactionQuery->where('ticket_transactions.price', $data['amount']);
            $hospitalitySuiteQuery->where('hospitality_suite_transactions.price', $data['amount']);
            $consumerMembershipPackageQuery->where('consumer_membership_package.price', $data['amount']);
        }

        if (isset($data['currency']) && trim($data['currency']) != '') {
            $productTransactionQuery->where('product_transactions.currency', $data['currency']);
            $eventTransactionQuery->where('event_transactions.currency', $data['currency']);
            $ticketTransactionQuery->where('ticket_transactions.currency', $data['currency']);
            $hospitalitySuiteQuery->where('hospitality_suite_transactions.currency', $data['currency']);
            $consumerMembershipPackageQuery->where('consumer_membership_package.currency', $data['currency']);
        }

        if (isset($data['from_date']) && !empty($data['from_date'])) {
            $productTransactionQuery->whereDate('product_transactions.transaction_timestamp', '>=', convertDateFormat($data['from_date'], config('fanslive.DATE_CMS_FORMAT.php')));
            $eventTransactionQuery->whereDate('event_transactions.transaction_timestamp', '>=', convertDateFormat($data['from_date'], config('fanslive.DATE_CMS_FORMAT.php')));
            $ticketTransactionQuery->whereDate('ticket_transactions.transaction_timestamp', '>=', convertDateFormat($data['from_date'], config('fanslive.DATE_CMS_FORMAT.php')));
            $hospitalitySuiteQuery->whereDate('hospitality_suite_transactions.transaction_timestamp', '>=', convertDateFormat($data['from_date'], config('fanslive.DATE_CMS_FORMAT.php')));
            $consumerMembershipPackageQuery->whereDate('consumer_membership_package.transaction_timestamp', '>=', convertDateFormat($data['from_date'], config('fanslive.DATE_CMS_FORMAT.php')));
        }

        if (isset($data['to_date']) && !empty($data['to_date'])) {
            $productTransactionQuery->whereDate('product_transactions.transaction_timestamp', '<=', convertDateFormat($data['to_date'], config('fanslive.DATE_CMS_FORMAT.php')));
            $eventTransactionQuery->whereDate('event_transactions.transaction_timestamp', '<=', convertDateFormat($data['to_date'], config('fanslive.DATE_CMS_FORMAT.php')));
            $ticketTransactionQuery->whereDate('ticket_transactions.transaction_timestamp', '<=', convertDateFormat($data['to_date'], config('fanslive.DATE_CMS_FORMAT.php')));
            $hospitalitySuiteQuery->whereDate('hospitality_suite_transactions.transaction_timestamp', '<=', convertDateFormat($data['to_date'], config('fanslive.DATE_CMS_FORMAT.php')));
            $consumerMembershipPackageQuery->whereDate('consumer_membership_package.transaction_timestamp', '<=', convertDateFormat($data['to_date'], config('fanslive.DATE_CMS_FORMAT.php')));
        }

        $productEventTransactionData = $productTransactionQuery->unionAll($eventTransactionQuery);
        $productEventTicketTransactionData = $productEventTransactionData->unionAll($ticketTransactionQuery);
        $productEventTicketHospitalitySuiteTransactionData = $productEventTicketTransactionData->unionAll($hospitalitySuiteQuery);
        $transactionData = $productEventTicketHospitalitySuiteTransactionData->unionAll($consumerMembershipPackageQuery);

        if (isset($data['sortby'])) {
            $sortby = $data['sortby'];
            $sorttype = $data['sorttype'];
        } else {
            $sortby = 'transaction_timestamp';
            $sorttype = 'desc';
        }

        $transactionData = $transactionData->orderBy($sortby, $sorttype);

        $transactionListArray = [];
        if (!array_key_exists('pagination', $data)) {
            $transactionData = $transactionData->paginate($data['pagination_length']);
            $transactionListArray = $transactionData;
        } else {
            $transactionListArray['data'] = $transactionData->get();
            $transactionListArray['total'] = count($transactionListArray['data']);
        }

        $response = $transactionListArray;

        return $response;
    }

    /**
     * Get payment cards
     *
     * @return mixed
     */
    public function getPaymentCards()
    {
        $ticketPaymentBrands = $this->ticketRepository->getPaymentCardType();
        $productPaymentBrands = $this->productRepository->getPaymentCardType();
        $eventPaymentBrands = $this->eventRepository->getPaymentCardType();
        $hospitalitySuitePaymentBrands = $this->hospitalitySuiteRepository->getPaymentCardType();
        $consumenrMembershipPackagePaymentBrands = $this->consumerMembershipPackageRepository->getPaymentCardType();
        $productTicketPaymentBrands = $ticketPaymentBrands->merge($productPaymentBrands);
        $eventProductTicketPaymentBrands = $productTicketPaymentBrands->merge($eventPaymentBrands);
        $hospitalitySuiteEventProductTicketPaymentBrands = $eventProductTicketPaymentBrands->merge($hospitalitySuitePaymentBrands);
        $finalPaymentCards = $hospitalitySuiteEventProductTicketPaymentBrands->merge($consumenrMembershipPackagePaymentBrands);
        $plucked = $finalPaymentCards->pluck('payment_brand');
        $tempData = $plucked->all();
        $uniquePaymentCards = array_filter(array_unique($tempData));
        return $uniquePaymentCards;
    }

    /**
     * Get transactions data.
     *
     * @param $id
     * @param $type
     *
     * @return mixed
     */
    public function getTransactionDetail($id, $type)
    {
        $transactionDetail = '';
        $tempProductTransactionType = array_keys(Config('fanslive.CATEGORY_TYPE'));
        $productTransactionsType = array_merge($tempProductTransactionType, ['product']);
        if (in_array($type, $productTransactionsType)) {
            $transactionDetail = ProductTransaction::find($id);
        } else if ($type == 'event') {
            $transactionDetail = EventTransaction::find($id);
        } else if ($type == 'ticket') {
            $transactionDetail = TicketTransaction::find($id);
        } else if ($type == 'hospitality') {
            $transactionDetail = HospitalitySuiteTransaction::find($id);
        } else if ($type == 'membership') {
            $transactionDetail = ConsumerMembershipPackage::find($id);
        }
        return $transactionDetail;
    }

    /**
     * Get purchase transaction details.
     *
     * @param $id
     * @param $type
     *
     * @return mixed
     */
    public function purchaseTransactionDetails($id, $type)
    {
        $transactionDetail = '';
        $tempProductTransactionType = array_keys(Config('fanslive.CATEGORY_TYPE'));
        $productTransactionsType = array_merge($tempProductTransactionType, ['product']);
        if (in_array($type, $productTransactionsType)) {
            $transactionDetail = ProductTransaction::find($id);
        } else if ($type == 'event') {
            $transactionDetail = EventTransaction::find($id);
        } else if ($type == 'ticket') {
            $transactionDetail = TicketTransaction::find($id);
        } else if ($type == 'hospitality') {
            $transactionDetail = HospitalitySuiteTransaction::find($id);
        } else if ($type == 'membership') {
            $transactionDetail = ConsumerMembershipPackage::find($id);
        }
        return $transactionDetail;
    }

    /**
     * Get all transaction for review
     *
     * @param $currency
     * @param $consumerId
     *
     * @return mixed
     */
    public function getAllTransactionForReview($currency = null, $consumerId = null)
    {
        $productTransactionQuery = $this->productRepository->getProductTransactionQueryForTransactions();
        $eventTransactionQuery = $this->eventRepository->getEventTransactionQueryForTransactions();
        $ticketTransactionQuery = $this->ticketRepository->getTicketTransactionQueryForTransactions();
        $hospitalitySuiteQuery = $this->hospitalitySuiteRepository->getHospitalitySuiteTransactionQueryForTransactions();
        $consumerMembershipPackageQuery = $this->consumerMembershipPackageRepository->getConsumerMembershipPackageQueryForTransactions();

        // Where payment_status = Unpaid
        $productTransactionQuery->where('product_transactions.payment_status', Config('fanslive.PAYMENT_STATUS.unpaid'));
        $eventTransactionQuery->where('event_transactions.payment_status', Config('fanslive.PAYMENT_STATUS.unpaid'));
        $ticketTransactionQuery->where('ticket_transactions.payment_status', Config('fanslive.PAYMENT_STATUS.unpaid'));
        $hospitalitySuiteQuery->where('hospitality_suite_transactions.payment_status', Config('fanslive.PAYMENT_STATUS.unpaid'));
        $consumerMembershipPackageQuery->where('consumer_membership_package.payment_status', Config('fanslive.PAYMENT_STATUS.unpaid'));

        // Where status = Success
        $productTransactionQuery->where('product_transactions.status', Config('fanslive.TRANSACTION_STATUS.success'));
        $eventTransactionQuery->where('event_transactions.status', Config('fanslive.TRANSACTION_STATUS.success'));
        $ticketTransactionQuery->where('ticket_transactions.status', Config('fanslive.TRANSACTION_STATUS.success'));
        $hospitalitySuiteQuery->where('hospitality_suite_transactions.status', Config('fanslive.TRANSACTION_STATUS.success'));
        $consumerMembershipPackageQuery->where('consumer_membership_package.status', Config('fanslive.TRANSACTION_STATUS.success'));

        if (!empty($currency)) {
            $productTransactionQuery->where('product_transactions.currency', $currency);
            $eventTransactionQuery->where('event_transactions.currency', $currency);
            $ticketTransactionQuery->where('ticket_transactions.currency', $currency);
            $hospitalitySuiteQuery->where('hospitality_suite_transactions.currency', $currency);
            $consumerMembershipPackageQuery->where('consumer_membership_package.currency', $currency);
        }

        if (!empty($consumerId)) {
            $productTransactionQuery->where('product_transactions.consumer_id', $consumerId);
            $eventTransactionQuery->where('event_transactions.consumer_id', $consumerId);
            $ticketTransactionQuery->where('ticket_transactions.consumer_id', $consumerId);
            $hospitalitySuiteQuery->where('hospitality_suite_transactions.consumer_id', $consumerId);
            $consumerMembershipPackageQuery->where('consumer_membership_package.consumer_id', $consumerId);
        }

        $productEventTransactionData = $productTransactionQuery->unionAll($eventTransactionQuery);
        $productEventTicketTransactionData = $productEventTransactionData->unionAll($ticketTransactionQuery);
        $productEventTicketHospitalitySuiteTransactionData = $productEventTicketTransactionData->unionAll($hospitalitySuiteQuery);
        $transactionData = $productEventTicketHospitalitySuiteTransactionData->unionAll($consumerMembershipPackageQuery);

        if (!empty($consumerId)) {
            return $transactionData->get()->sortby('transaction_timestamp');
        } else {
            $transactionListArray = [];
            $transactionListArray['data'] = $transactionData->get()
                                                            ->groupBy('consumer_id')
                                                            ->map
                                                            ->sortBy('transaction_timestamp')
                                                            ;
            $transactionListArray['total'] = count($transactionListArray['data']);
            return $transactionListArray;
        }
    }

    /**
     * Logic for export transactions
     *
     * @param $currency
     *
     * @return mixed
     */
    public function exportTransactions($currency = null)
    {
        $transactionGroups = $this->getAllTransactionForReview(strtoupper($currency));
        return $transactionGroups;
    }

    /**
     * Mark transactions as paid
     *
     * @param $currency
     *
     * @return mixed
     */
    public function markTransactionsAsPaid($currency = null)
    {
        $productTransactionQuery = $this->productRepository->getProductTransactionQueryForTransactions();
        $eventTransactionQuery = $this->eventRepository->getEventTransactionQueryForTransactions();
        $ticketTransactionQuery = $this->ticketRepository->getTicketTransactionQueryForTransactions();
        $hospitalitySuiteQuery = $this->hospitalitySuiteRepository->getHospitalitySuiteTransactionQueryForTransactions();
        $consumerMembershipPackageQuery = $this->consumerMembershipPackageRepository->getConsumerMembershipPackageQueryForTransactions();

        // Where payment_status = Unpaid
        $productTransactionQuery->where('product_transactions.payment_status', Config('fanslive.PAYMENT_STATUS.unpaid'));
        $eventTransactionQuery->where('event_transactions.payment_status', Config('fanslive.PAYMENT_STATUS.unpaid'));
        $ticketTransactionQuery->where('ticket_transactions.payment_status', Config('fanslive.PAYMENT_STATUS.unpaid'));
        $hospitalitySuiteQuery->where('hospitality_suite_transactions.payment_status', Config('fanslive.PAYMENT_STATUS.unpaid'));
        $consumerMembershipPackageQuery->where('consumer_membership_package.payment_status', Config('fanslive.PAYMENT_STATUS.unpaid'));

        // Where status = Success
        $productTransactionQuery->where('product_transactions.status', Config('fanslive.TRANSACTION_STATUS.success'));
        $eventTransactionQuery->where('event_transactions.status', Config('fanslive.TRANSACTION_STATUS.success'));
        $ticketTransactionQuery->where('ticket_transactions.status', Config('fanslive.TRANSACTION_STATUS.success'));
        $hospitalitySuiteQuery->where('hospitality_suite_transactions.status', Config('fanslive.TRANSACTION_STATUS.success'));
        $consumerMembershipPackageQuery->where('consumer_membership_package.status', Config('fanslive.TRANSACTION_STATUS.success'));

        if (!empty($currency)) {
            $productTransactionQuery->where('product_transactions.currency', $currency);
            $eventTransactionQuery->where('event_transactions.currency', $currency);
            $ticketTransactionQuery->where('ticket_transactions.currency', $currency);
            $hospitalitySuiteQuery->where('hospitality_suite_transactions.currency', $currency);
            $consumerMembershipPackageQuery->where('consumer_membership_package.currency', $currency);
        }

        // Update payment_status to Paid
        $productTransactionStatus = $productTransactionQuery->update(['payment_status' => Config('fanslive.PAYMENT_STATUS.paid')]);
        $eventTransactionStatus = $eventTransactionQuery->update(['payment_status' => Config('fanslive.PAYMENT_STATUS.paid')]);
        $ticketTransactionStatus = $ticketTransactionQuery->update(['payment_status' => Config('fanslive.PAYMENT_STATUS.paid')]);
        $hospitalitySuiteStatus = $hospitalitySuiteQuery->update(['payment_status' => Config('fanslive.PAYMENT_STATUS.paid')]);
        $consumerMembershipPackageStatus = $consumerMembershipPackageQuery->update(['payment_status' => Config('fanslive.PAYMENT_STATUS.paid')]);

        return TRUE;
    }

    /**
     * Get transaction for dashboard
     *
     * @param $clubId
     * @param $type = 'count' or 'price_total'
     *
     * @return array
     */
    public function getTransactionsForDashboard($clubId, $type='count')
    {
        $responseArr = [];
        $ticketTransactionQuery = $this->ticketRepository->getTicketTransactionQueryForTransactions();
        $productTransactionQuery = $this->productRepository->getProductTransactionQueryForTransactions();
        $consumerMembershipPackageQuery = $this->consumerMembershipPackageRepository->getConsumerMembershipPackageQueryForTransactions();
        if (!empty($clubId)) {
            $ticketTransactionQuery->where('ticket_transactions.club_id','=',$clubId);
            $ticketTransactionQuery->where('ticket_transactions.status','=',config('fanslive.TRANSACTION_STATUS.success'));

            $productTransactionQuery->where('product_transactions.club_id','=',$clubId);
            $productTransactionQuery->where('product_transactions.status','=',config('fanslive.TRANSACTION_STATUS.success'));

            $consumerMembershipPackageQuery->where('consumer_membership_package.club_id','=',$clubId);
            $consumerMembershipPackageQuery->where('consumer_membership_package.status','=',config('fanslive.TRANSACTION_STATUS.success'));
        }

        $productTransactionsData = $productTransactionQuery->get()->groupBy('transaction_type')->map->sortby('transaction_timestamp');

        if ($type == 'count') {
            $responseArr['ticket'] = $ticketTransactionQuery->count();
            $responseArr['food_and_drink'] = isset($productTransactionsData['food_and_drink']) ? $productTransactionsData['food_and_drink']->count() : 0;
            $responseArr['merchandise'] = isset($productTransactionsData['merchandise']) ? $productTransactionsData['merchandise']->count() : 0;
            $responseArr['membership'] = $consumerMembershipPackageQuery->count();
        } else if ($type == 'price_total') {
            $responseArr['ticket'] = $ticketTransactionQuery->sum('price');
            $responseArr['food_and_drink'] = isset($productTransactionsData['food_and_drink']) ? $productTransactionsData['food_and_drink']->sum('price') : 0;
            $responseArr['merchandise'] = isset($productTransactionsData['merchandise']) ? $productTransactionsData['merchandise']->sum('price') : 0;
            $responseArr['membership'] = $consumerMembershipPackageQuery->sum('price');
        }
        return $responseArr;
    }

	/**
	 * Handle logic to get Sum of all Transaction of GBP Currency.
	 *
	 *
	 * @return mixed
	 */
	public function getGbpTransactionSum()
	{
		$productTransactionSum = ProductTransaction::where('status', 'successful')->where('currency', 'GBP')->count();
		$eventTransactionSum = EventTransaction::where('status', 'successful')->where('currency', 'GBP')->count();
		$ticketTransactionSum = TicketTransaction::where('status', 'successful')->where('currency', 'GBP')->count();
		$hospitalitySuiteSum = HospitalitySuiteTransaction::where('status', 'successful')->where('currency', 'GBP')->count();
		$consumerMembershipPackageSum = ConsumerMembershipPackage::where('status', 'successful')->where('currency', 'GBP')->count();

		$gbpTransactionsSum = $productTransactionSum + $eventTransactionSum + $ticketTransactionSum + $hospitalitySuiteSum + $consumerMembershipPackageSum;

		return $gbpTransactionsSum;
	}

	/**
	 * Handle logic to get Sum of all Transaction of EUR Currency.
	 *
	 *
	 * @return mixed
	 */
	public function getEurTransactionSum()
	{
		$productTransactionEurSum = ProductTransaction::where('status', 'successful')->where('currency', 'EUR')->count();
		$eventTransactionEurSum = EventTransaction::where('status', 'successful')->where('currency', 'EUR')->count();
		$ticketTransactionEurSum = TicketTransaction::where('status', 'successful')->where('currency', 'EUR')->count();
		$hospitalitySuiteEurSum = HospitalitySuiteTransaction::where('status', 'successful')->where('currency', 'EUR')->count();
		$consumerMembershipPackageEurSum = ConsumerMembershipPackage::where('status', 'successful')->where('currency', 'EUR')->count();

		$eurTransactionsSum = $productTransactionEurSum + $eventTransactionEurSum + $ticketTransactionEurSum + $hospitalitySuiteEurSum + $consumerMembershipPackageEurSum;

		return $eurTransactionsSum;
	}

    public function getTransactionStatus()
    {
        $userId = 'TEST_USER';
        $transactionId = 123;
        $params = [
            'sessionId' => 1234567
        ];
       return $this->frontService->getUsersTransactionMethodsStatus($userId, $sessionId, $params);
    }

    public function getUsersOngoingTransactions()
    {
        $userId = 'TEST_USER';
        $params = [
            'sessionId' => 1234567
        ];
        return  $this->frontService->getUsersTransactionMethods($userId, $params);
    }
}
