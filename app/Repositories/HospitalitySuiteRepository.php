<?php

namespace App\Repositories;

use App\Models\HospitalitySuite;
use App\Models\Match;
use App\Models\HospitalityDietaryOptions;
use App\Models\HospitalitySuiteTransaction;
use App\Models\BookedHospitalityTransactionDietaryOptions;
use App\Models\BookedHospitalitySuite;
use App\Models\HospitalitySuiteNotification;
use DB;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use App\Models\MatchHospitality;
use App\Models\MatchHospitalityHospitalitySuite;

class HospitalitySuiteRepository extends BaseRepository
{
	public function getData($data, $clubId)
	{
		$hospitalitySuiteData = DB::table('hospitality_suites')->where('club_id', $clubId);

		if (isset($data['sortby'])) {
			$sortby = $data['sortby'];
			$sorttype = $data['sorttype'];
		} else {
			$sortby = 'hospitality_suites.id';
			$sorttype = 'desc';
		}
		$hospitalitySuiteData = $hospitalitySuiteData->orderBy($sortby, $sorttype);

		$hospitalitySuiteListArray = [];
		if (!array_key_exists('pagination', $data)) {
			$hospitalitySuiteData = $hospitalitySuiteData->paginate($data['pagination_length']);
			$hospitalitySuiteListArray = $hospitalitySuiteData;
		} else {
			$hospitalitySuiteListArray['total'] = $hospitalitySuiteData->count();
			$hospitalitySuiteListArray['data'] = $hospitalitySuiteData->get();
		}

		$response = $hospitalitySuiteListArray;

		return $response;
	}

	/**
	 * Handle logic to create a Hospitality Suite.
	 *
	 * @param $user
	 * @param $data
	 *
	 * @return mixed
	 */
	public function create($clubId, $user, $data)
	{
		$options = [];
		if (isset($data['dietary_options']) && !empty($data['dietary_options'])) {
			$options = array_values(array_filter(array_map('array_filter', $data['dietary_options'])));
		}
		$hospitalitySuites = HospitalitySuite::create([
			'title' => $data['name'],
			'club_id' => $clubId,
			'price' => $data['price'],
			'image' => $data['image'],
			'image_file_name' => $data['image_file_name'],
			'number_of_seat'=> $data['number_of_seat'],
			'short_description' => $data['short_description'],
			'long_description' => $data['long_description'],
			'is_active' => isset($data['is_active']) ? $data['is_active'] : 0,
			'vat_rate' => $data['vat_rate'],
			'created_by' => $user->id,
			'updated_by' => $user->id,
		]);
		if($hospitalitySuites)
		{
			foreach ($options as $key => $value) {
				$HospitalityDietaryOptions= HospitalityDietaryOptions::create([
					'hospitality_suite_id'=>$hospitalitySuites->id,
					'option_name' =>$value['dietary_options'],
				]);
			}
		}

		return $hospitalitySuites;
	}

	/**
	 * Handle logic to update a Hospitality Suite.
	 *
	 * @param $user
	 * @param $data
	 *
	 * @return mixed
	 */
	public function update($user, $hospitalitySuites, $data)
	{
		$options = [];
		if (isset($data['dietary_options']) && !empty($data['dietary_options'])) {
			$options = array_values(array_filter(array_map('array_filter', $data['dietary_options'])));
		}
		$hospitalitySuites->fill([
			'title' => $data['name'],
			'price' => $data['price'],
			'image' => $data['image'],
			'image_file_name' => $data['image_file_name'],
			'number_of_seat'=> $data['number_of_seat'],
			// 'seating_plan' => $data['seating_plan'],
			// 'seating_plan_file_name' => $data['seating_plan_file_name'],
			'short_description' => $data['short_description'],
			'long_description' => $data['long_description'],
			'is_active' => isset($data['is_active']) ? $data['is_active'] : 0,
			'vat_rate' => $data['vat_rate'],
			'created_by' => $user->id,
		]);
		$hospitalitySuites->save();

		$staff = HospitalityDietaryOptions::where('hospitality_suite_id', $hospitalitySuites->id)->delete();
		if($hospitalitySuites)
		{
			foreach ($options as $key => $value) {
				$HospitalityDietaryOptions= HospitalityDietaryOptions::create([
					'hospitality_suite_id'=>$hospitalitySuites->id,
					'option_name' =>$value['dietary_options'],
				]);
			}
		}

		return $hospitalitySuites;
	}

	/**
	 * Handle logic to create a new hospitality suite.
	 *
	 * @param $data
	 */
	public function createHospitalitySuitePurchase($data)
	{
		$hospitality = HospitalitySuiteTransaction::create([
			'hospitality_suite_id' => $data['hospitality_suite_id'],
			'match_id'=>$data['match_id'],
			'club_id' => $data['club_id'],
			'consumer_id' => $data['consumer_id'],
			'price' => $data['price'],
			'currency' => $data['currency'],
			'per_quantity_price' => $data['per_quantity_price'],
			'vat_rate' => $data['vat_rate'],
			'transaction_reference_id' => $data['transaction_reference_id'],
			'card_details'=>$data['card_details'],
			'payment_status'=>$data['payment_status'],
			'custom_parameters' => $data['custom_parameters'],
		]);
		return $hospitality;
	}

	/**
	 * Save booked hospitality.
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function saveBookedHospitalitySuite($data)
	{
		$bookedHospitality = BookedHospitalitySuite::create([
			'hospitality_suite_transaction_id' => $data['hospitality_suite_transaction_id'],
			'seat' => $data['seat'],
		]);
		return $bookedHospitality;
	}

	/**
	 * Handle logic to update hospitality suite details.
	 *
	 * @param $data
	 * @param $hospitalitySuiteTransactionId
	 */
	public function updateHospitalitySuitePurchase($data, $hospitalitySuite)
	{
        $hospitalitySuite->status = $data['status'];
		$hospitalitySuite->psp_reference_id = $data['psp_reference_id'];
		$hospitalitySuite->payment_method = $data['payment_method'];
		$hospitalitySuite->psp = $data['psp'];
		$hospitalitySuite->status_code = $data['status_code'];
		$hospitalitySuite->psp_account = $data['psp_account'];
		$hospitalitySuite->transaction_timestamp = $data['transaction_timestamp'];
		$hospitalitySuite->save();
		return $hospitalitySuite;
	}

	/**
     * Get hospitality suite transactions query.
     *
     * @param $clubId
     * @param $data
     *
     * @return mixed
     */
    public function getHospitalitySuiteTransactionQueryForTransactions()
    {
        $hospitalitySuiteQuery = DB::table('hospitality_suite_transactions')->select(
            'hospitality_suite_transactions.id as id',
            'clubs.name as club',
            'clubs.time_zone as club_time_zone',
            'hospitality_suite_transactions.consumer_id as consumer_id',
            'hospitality_suite_transactions.club_id as club_id',
            //'hospitality_suite_transactions.payment_type as payment_type',
            'hospitality_suite_transactions.payment_brand as payment_brand',
            'hospitality_suite_transactions.price as price',
            'hospitality_suite_transactions.fee as fee',
            'hospitality_suite_transactions.currency as currency',
            'hospitality_suite_transactions.status as status',
            'hospitality_suite_transactions.payment_status as payment_status',
            'hospitality_suite_transactions.transaction_timestamp as transaction_timestamp',
            'users.email as email',
            DB::raw('"hospitality" as transaction_type'),
            DB::raw('ROUND(hospitality_suite_transactions.price*(hospitality_suite_transactions.fee/100),2) as fee_amount'),
            DB::raw('CONCAT(users.first_name," ", users.last_name) as name')
        )
        ->leftJoin('consumers', 'consumers.id', '=', 'hospitality_suite_transactions.consumer_id')
        ->leftJoin('users', 'users.id', '=', 'consumers.user_id')
        ->leftJoin('clubs', 'clubs.id', '=', 'hospitality_suite_transactions.club_id')
        ;
        return $hospitalitySuiteQuery;
    }

    /**
     * Get hospitality suite transactions payment brand.
     *
     * @param $clubId
     * @param $data
     *
     * @return mixed
     */
    public function getPaymentCardType()
    {
        $paymentBrands = DB::table('hospitality_suite_transactions')->select('payment_brand')->groupBy('payment_brand')->get();
        return $paymentBrands;
    }
	 /**
	 * Handle logic to save hospitality suite dietary option.
	 *
	 * @param $hospitalitySuiteTransactionId
	 * @param $hospitalitySuitsObj
	 *
	 * @return mixed
	 */
	public function saveBookedHospitalitySuiteDietaryOption($hospitalitySuiteTransactionId, $data)
    {
		foreach($data as $val) {
			$bookedHospitalityTransactionDietaryOptions = BookedHospitalityTransactionDietaryOptions::create([
				'hospitality_suite_transaction_id' => $hospitalitySuiteTransactionId,
				'hospitality_suite_dietary_option_id' => $val->hospitality_dietary_option_id,
				'quantity' => $val->selected_quantity,
			]);
		}
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
		return HospitalitySuite::join('match_hospitality_hospitality_suites', 'match_hospitality_hospitality_suites.hospitality_suite_id','=','hospitality_suites.id')
								->join('match_hospitalities', 'match_hospitalities.id','=','match_hospitality_hospitality_suites.match_hospitality_id')
								->select('hospitality_suites.*')
								->where('hospitality_suites.is_active', 1)
								->where('match_hospitalities.match_id','=',$matchId)
								->get();
	}

	/**
	 * Save hospitality suite notification
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function saveHospitalitySuiteNotification($data)
	{
		$hospitalitySuiteNotification = HospitalitySuiteNotification::create([
			'consumer_id' => $data['consumer_id'],
			'club_id' => $data['club_id'],
			'hospitality_suite_id' => $data['hospitality_suite_id'],
			'reason' => $data['reason'],
			'is_notified' => Arr::get($data, 'is_notified', 0),
		]);

		return $hospitalitySuiteNotification;
	}
	/**
     * Handle logic to get event transaction data.
     * @param transactionReferenceId
     *
     * @return mixed
     */
    public function getHospitalitySuiteTransactionData($transactionReferenceId)
    {
        return HospitalitySuiteTransaction::where('transaction_reference_id',$transactionReferenceId)->get()->first();
    }
    /**
    * Get all Data
    */
    public function getAllBookedHospitalitySuites()
    {
    	return BookedHospitalitySuite::all();
    }
}
