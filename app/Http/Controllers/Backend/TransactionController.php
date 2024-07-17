<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\TransactionService;
use App\Models\User;
use App\Models\Club;
use App\Models\Consumer;
use App\Models\Setting;
use App\Exports\TransactionsExport;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use JavaScript;
use Auth;

class TransactionController extends Controller
{
    /**
     * The transaction service instance.
     *
     * @var service
     */
    public function __construct(TransactionService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($club)
    {
        $clubId = getClubIdBySlug($club);
        $clubCurrency = Club::findOrFail($clubId)->currency;
        $paymentCards = $this->service->getPaymentCards();
        $paymentStatuses = config('fanslive.PAYMENT_STATUS');
        $consumers = User::where(['type' => 'Consumer'])->get(['id','email']);
        JavaScript::put([
            'dateTimeCMSFormat' => config('fanslive.DATE_TIME_CMS_FORMAT.js')
        ]);
    	return view('backend.transactions.index', compact('consumers', 'clubCurrency', 'paymentCards', 'paymentStatuses'));
    }

    /**
     * Get transaction list data.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTransactionsData(Request $request, $club=null)
    {
        $requestData = $request->all();
        $clubId = getClubIdBySlug($club);
        $clubCurrency = Club::findOrFail($clubId)->currency;
        $requestData['currency'] = $clubCurrency;
        $transactionsList = $this->service->getData(
            $clubId,
            $requestData
        );
        return $transactionsList;
    }

    /**
     * Get transaction detail.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTransactionDetail($club = null, $id, $type)
    {
        $transaction = $this->service->getTransactionDetail($id, $type);
        $club = Club::find(getClubIdBySlug($club));
        $cardDetails = '';
        if ($transaction && $transaction->card_details) {
            $cardDetails = json_decode($transaction->card_details, TRUE);
        }
        $purchaseTransactionDetails = $this->service->purchaseTransactionDetails($id, $type);
        return view('backend.transactions.detail', compact('transaction', 'cardDetails', 'type','club'));
    }

    /**
     * Show the form the update the transaction status.
     *
     * @param $club
     * @param $id
     * @param $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showStatusForm($club, $id, $type)
    {
        $transaction = $this->service->getTransactionDetail($id, $type);
        return view('backend.transactions.status', compact('transaction', 'club', 'type'));
    }

    /**
     * Update the transaction status.
     *
     * @param Request $request
     * @param $club
     * @param $id
     * @param $type
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, $club, $id, $type)
    {
        $data = $request->validate([
            'payment_status' => [
                'required',
                Rule::in(config('fanslive.PAYMENT_STATUS')),
            ],
        ]);

        $transaction = $this->service->getTransactionDetail($id, $type);
        if ($transaction) {
            $transaction->payment_status = $data['payment_status'];
            if($transaction->save()) {
                flash('Transaction updated successfully')->success();
            }
            else {
                flash('Transaction could not be updated. Please try again.')->error();
            }
        } else {
            flash('Transaction not found.')->error();
        }
        return redirect()->back();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function report($type)
    {
        $clubCurrency = $type;
        $paymentCards = $this->service->getPaymentCards();
        $consumers = User::where(['type' => 'Consumer'])->get(['id','email']);
        $currentPanel = 'clubadmin';
        if (Auth::user()->hasRole('superadmin')) {
            $currentPanel = 'superadmin';
        }
        $paymentStatuses = config('fanslive.PAYMENT_STATUS');
        $clubs = Club::orderBy('name','asc')->get(['id','name']);
        JavaScript::put([
            'currency' => $clubCurrency,
            'currentPanel' => $currentPanel,
            'dateTimeCMSFormat' => config('fanslive.DATE_TIME_CMS_FORMAT.js')
        ]);
        return view('backend.transactions.index', compact('consumers', 'clubCurrency', 'clubs', 'paymentCards', 'paymentStatuses'));
    }

    /**
     * Get transaction list data.
     *
     * @return \Illuminate\Http\Response
     */
    public function getReportData(Request $request)
    {
        $requestData = $request->all();
        $clubId = null;
        if(isset($requestData['club_id']) && !empty($requestData['club_id'])) {
            $clubId = $requestData['club_id'];
        }
        $transactionsList = $this->service->getData(
            $clubId,
            $requestData
        );
        return $transactionsList;
    }

    /**
     * Get report transaction detail.
     *
     * @return \Illuminate\Http\Response
     */
    public function getReportTransactionDetail($id, $type)
    {
        $transaction = $this->service->getTransactionDetail($id, $type);
        $cardDetails = '';
        if ($transaction && $transaction->card_details) {
            $cardDetails = json_decode($transaction->card_details, TRUE);
        }
        return view('backend.transactions.detail', compact('transaction', 'cardDetails', 'type'));
    }

    /**
     * Show the form the update the transaction status.
     *
     * @param $club
     * @param $id
     * @param $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showReportStatusForm($id, $type)
    {
        $club = null;
        $currentPanel = 'clubadmin';
        if (Auth::user()->hasRole('superadmin')) {
            $currentPanel = 'superadmin';
        }
        $transaction = $this->service->getTransactionDetail($id, $type);
        return view('backend.transactions.status', compact('transaction', 'club', 'currentPanel', 'type'));
    }

    /**
     * Update the transaction status.
     *
     * @param Request $request
     * @param $id
     * @param $type
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateReportStatus(Request $request, $id, $type)
    {
        $data = $request->validate([
            'payment_status' => [
                'required',
                Rule::in(config('fanslive.PAYMENT_STATUS')),
            ],
        ]);

        $transaction = $this->service->getTransactionDetail($id, $type);
        if ($transaction) {
            $transaction->payment_status = $data['payment_status'];
            if($transaction->save()) {
                flash('Transaction updated successfully')->success();
            }
            else {
                flash('Transaction could not be updated. Please try again.')->error();
            }
        } else {
            flash('Transaction not found.')->error();
        }
        return redirect()->back();
    }

    /**
     * Handle review transaction logic
     *
     * @param Request $request
     * @param $type
     * @return \Illuminate\Http\RedirectResponse
     */
    public function review(Request $request, $type)
    {
        $bankFee = 0;
        $setting = Setting::where('key','bank_fee')->first();
        if ($setting) {
            $bankFee = $setting->value;
        }
        JavaScript::put([
            'currency' => $type,
            'currencySymbol' => Config('fanslive.CURRENCY_SYMBOL.'.strtoupper($type)),
            'dateTimeCMSFormat' => config('fanslive.DATE_TIME_CMS_FORMAT.js'),
            'bankFee' => $bankFee
        ]);
        return view('backend.transactions.review', compact('type'));
    }

    /**
     * Get review transaction list data.
     *
     * @return \Illuminate\Http\Response
     */
    public function reviewData(Request $request, $currency)
    {
        $transactions = $this->service->getAllTransactionForReview($currency);
        return $transactions;
    }

    /**
     * Get review transaction by consumer id
     *
     * @param Request $request
     * @param $type
     * @param $consumerId
     *
     * @return \Illuminate\Http\Response
     */
    public function getConsumerReviewTransactions(Request $request, $type, $consumerId)
    {
        $transactions = $this->service->getAllTransactionForReview(strtoupper($type), $consumerId);
        $consumer = Consumer::findOrFail($consumerId);
        return view('backend.transactions.review-detail', compact('transactions', 'type', 'consumer'));
    }

    /**
     * Fetch the transactions report data for export.
     *
     * @param $type
     * 
     * @return Maatwebsite\Excel\Facades\Excel
     */
    public function export($type)
    {
        $bankFee = 0;
        $setting = Setting::where('key','bank_fee')->first();
        if ($setting) {
            $bankFee = $setting->value;
        }
        $transactions = $this->service->exportTransactions($type);
        $this->service->markTransactionsAsPaid($type);
        return Excel::download(new TransactionsExport($transactions, $bankFee, $type), strtoupper($type).date('dMy').'.csv');
    }

    /**
     * unset class instance.
     */
    public function __destruct()
    {
        unset($this->service);
    }
}
