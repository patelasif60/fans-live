<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Consumer;

class TransactionsExport implements FromView
{

    /**
     * Transaction data
     *
     */
	protected $transactionData;

    /**
     * Bank fee
     *
     */
    protected $bankFee;

    /**
     * Type
     *
     */
    protected $type;

	public function __construct($transactionData, $bankFee, $type)
	{
		$this->transactionData = $transactionData;
        $this->bankFee = $bankFee;
        $this->type = $type;
	}

    public function view(): View
    {
        $tempTransactions = [];
        if (strtoupper($this->type) == 'EUR') {
            $formattedData = $this->formatEURReportData($this->transactionData['data']);
        } else if (strtoupper($this->type) == 'GBP') {
            $formattedData = $this->formatGBPReportData($this->transactionData['data']);            
        }
        return view('backend.exports.transactions', [
            'formattedData' => $formattedData,
            'type' => $this->type
        ]);
    }

    public function formatEURReportData($data) 
    {
        $referenceData = [
            'transaction_reference' => 'fanslive' . date('dMy'),
            'current_day' => now()->format('Ymd'),
            'remitter_bank_account' => ''
        ];

        $rows = collect();
        if ($data) {
            foreach ($data as $transactions) {
                $totalOwed = $totalGross = $totalFeeAmount = $totalNetAmount = 0; 
                foreach ($transactions as $transaction) {
                    $totalGross += $transaction->price;
                    $totalFeeAmount += $transaction->fee_amount;
                }
                $totalNetAmount = $totalGross - number_format($totalFeeAmount,2,'.','');
                $totalOwed = $totalNetAmount - $this->bankFee;
                $consumerId = $transactions->first()->consumer_id;
                $consumer = Consumer::findOrFail($consumerId);
                $rows->push([
                    [$referenceData['transaction_reference']],
                    [$referenceData['current_day']],
                    ['EUR'],
                    [number_format($totalOwed, 2, '.', '')],
                    [''], // 1 blank row
                    [$referenceData['remitter_bank_account']],
                    [$consumer->club->clubBankDetail->bic],
                    [''],[''],[''],[''],[''], // 5 blank rows
                    [$consumer->club->clubBankDetail->iban],
                    [$consumer->club->clubBankDetail->account_name],
                    [''],[''],[''], // 3 blank rows
                    ['payment'],
                    [''],[''],[''],[''],[''],[''],[''], // 7 blank rows
                ]);
            }
        }
        return $rows;
    }

    public function formatGBPReportData($data)
    {
        $referenceData = [
            'transaction_reference' => 'fanslive' . date('dMy'),
            'current_day' => now()->format('Ymd')
        ];

        $rows = collect();
        if ($data) {
            foreach ($data as $transactions) {
                $totalGross = $totalFeeAmount = $totalNetAmount = 0; 
                foreach ($transactions as $transaction) {
                    $totalGross += $transaction->price;
                    $totalFeeAmount += $transaction->fee_amount;
                }
                $totalNetAmount = $totalGross - number_format($totalFeeAmount,2,'.','');
                $totalOwed = $totalNetAmount - $this->bankFee;
                $consumerId = $transactions->first()->consumer_id;
                $consumer = Consumer::findOrFail($consumerId);
                $rows->push([
                    'sort_code'             => $consumer->club->clubBankDetail->sort_code,
                    'account_name'          => $consumer->club->clubBankDetail->account_name,
                    'account_number'        => $consumer->club->clubBankDetail->account_number,
                    'total_owed'            => number_format($totalOwed, 2, '.', ''),
                    'transaction_reference' => $referenceData['transaction_reference']
                ]);
            }
        }
        return $rows;
    }

}
