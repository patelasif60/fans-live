<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\HospitalitySuite\PaymentRequest;
use App\Models\ClubLoyaltyPointSetting;
use App\Models\HospitalitySuite;
use App\Models\Club;
use App\Services\HospitalitySuiteService;
use App\Services\LoyaltyRewardPointHistoryService;
use App\Services\MatchService;
use Illuminate\Http\Request;
use App\Http\Resources\HospitalitySuite\HospitalitySuite as HospitalitySuiteResource;
use App\Http\Resources\Match\Match as MatchResource;
use App\Http\Resources\HospitalitySuite\HospitalitySuiteCollection;
use App\Http\Requests\Api\HospitalitySuite\GetHospitalitySuiteRequest;
use App\Http\Requests\Api\HospitalitySuite\MakePaymentRequest;
use App\Http\Requests\Api\HospitalitySuite\ValidatePaymentRequest;
use App\Http\Requests\Api\HospitalitySuite\UpcomingMatchesForHospitalityRequest;
use App\Http\Requests\Api\HospitalitySuite\GetHospitalitySuiteDetailRequest;
use App\Http\Requests\Api\HospitalitySuite\SaveNotificationRequest;
use App\Models\Consumer;
use App\Models\HospitalitySuiteTransaction;
use App\Models\BookedHospitalitySuite;
use App\Http\Resources\HospitalitySuiteTransaction\HospitalitySuiteTransaction as HospitalitySuiteTransactionResource;
use JWTAuth;
use Illuminate\Support\Str;
use Mail;
use App\Mail\SendHospitalitySuiteTicketPDF;
use App\Http\Requests\Api\HospitalitySuite\EmailInPdfRequest;
use PDF;
use App\Jobs\SendHospitalitySuiteTransactionEmail;
use App\Services\UserService;

/**
 * @group Hospitality Suite
 *
 * APIs for Hospitality Suite.
 */

class HospitalitySuiteController extends Controller
{
	/**
	 * Create a hospitality suite service variable.
	 *
	 * @return void
	 */
	protected $service;
	protected $loyaltyRewardPointHistoryService;
	protected $matchService;
    protected $userService;
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(HospitalitySuiteService $service, LoyaltyRewardPointHistoryService $loyaltyRewardPointHistoryService, MatchService $matchService,UserService $userService)
	{
		$this->service = $service;
		$this->loyaltyRewardPointHistoryService = $loyaltyRewardPointHistoryService;
		$this->matchService = $matchService;
        $this->userService = $userService;
	}

	/**
	 * Get Hospitality Suites
	 * Get all Hospitality Suites.
	 *
	 * @bodyParam club_id int required An id of a club. Example: 1
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getHospitalitySuites(GetHospitalitySuiteRequest $request)
	{
		$consumer = getLoggedinConsumer();
		$match = $this->matchService->checkUpcomingMatchById($request->match_id);
		if (!isset($match)) {
			return response()->json([
				'message' => 'No match found.'
			], 400);
		}
		$hospitalitySuites = $this->service->getHospitalitySuiteByMatchId($request->match_id);
		$hospitalitySuitesData = HospitalitySuiteCollection::make($hospitalitySuites)->setMatchId($request->match_id);
		return $hospitalitySuitesData;
	}
	 /**
     * Get hospitality upcoming match.
     *
     * @return \Illuminate\Http\Response
     */
	public function getUpcomingMatchesForHospitality(UpcomingMatchesForHospitalityRequest $request)
	{
		$matches = $this->matchService->getUpcomingMatches($request->club_id);
	 	$data = MatchResource::collection($matches);
	 	return response()->json([
			'data' => $data,
		]);
	}

	/**
     * Get hospitality suite detail.
     *
     * @return \Illuminate\Http\Response
     */
	public function getHospitalitySuiteDetail(GetHospitalitySuiteDetailRequest $request)
	{
	 	$hospitalitySuite = HospitalitySuite::findOrFail($request['id']);
	 	return new HospitalitySuiteResource($hospitalitySuite);
	}
	/**
     * Email hositality suite tickets in pdf
     * Email hositality suite tickets in pdf.
     *
     * @bodyParam hospitality_suite_transaction_id int required The id of ticket transaction. Example: 1
     * @bodyParam email string required An email id of a user. Example: abc@example.com
     *
     * @return \Illuminate\Http\Response
     */
    public function emailHospitalitySuiteTicketsInPdf(EmailInPdfRequest $request)
    {
        $uuid = (String) Str::uuid();
        $hospitalitySuiteTransaction = HospitalitySuiteTransaction::find($request['transaction_id']);
        $bookedHospitalityTickets = new HospitalitySuiteTransactionResource($hospitalitySuiteTransaction);
        $clubDetail = Club::find($hospitalitySuiteTransaction->club_id);
        $hospitalityTicketsPdf = PDF::loadView('pdf.hospitality_ticket_detail', ['bookedHospitalityTickets' => $bookedHospitalityTickets, 'clubDetail' => $clubDetail]);
        $file = storage_path('hospitality_suite_ticket' . $uuid . '.pdf');
        $hospitalityTicketsPdf->save($file);
        Mail::to($request['email'])->send(new SendHospitalitySuiteTicketPDF($file));
        unlink($file);
        return response()->json([
            'message' => 'Email has been sent successfully.',
        ]);
    }

    /**
	 * Save hospitality suite notification
	 *
	 * @bodyParam hospitality_suite_id int required An id of a match. Example: 1
	 * @bodyParam reason enum required A reason of a notification. Example: unavailable
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function saveHospitalitySuiteNotification(SaveNotificationRequest $request)
	{
		$user = JWTAuth::user();

		return $this->service->saveHospitalitySuiteNotification($user, $request->all());
	}
	/**
	 * Make paymet for hospitality suites ticket
	 * Make paymet for hospitality suites ticket
	 *
	 * @bodyParam hospitality_suite_dietary_options required The json format.
	 * @bodyParam number_of_seats int required The Number of seat. Example: 1
	 * @bodyParam match_id int required The Match id. Example: 1091
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function makeHospitalityTicketPayment(MakePaymentRequest $request)
	{
		$user = JWTAuth::user();
		$consumer = Consumer::where('user_id', $user->id)->first();
		$data = $request->all();
		$hospitalityRewardPercentage = null;

		$hospitalitySuitsObj = json_decode($data['hospitality_suite_dietary_options']);
		$hospitalitySuite = HospitalitySuite::find($data['hospitality_suits_id']);
		$bookedSeatCount = $hospitalitySuite->getHospitalitySuiteTickets($hospitalitySuite,$data['match_id']);

		if(($hospitalitySuite->number_of_seat - $bookedSeatCount ) < $data['number_of_seats'] )
		{
			return response()->json([
				'message' => 'Ticket not available'
			], 400);
		}

		$hospitalitySuiteData = $this->service->calculateHospitalitySuiteTransactionPrice($data);

		$hospitalitySuiteTransaction = $this->service->updateHospitalitySuitePurchase($data,$hospitalitySuiteData,$consumer);

		if($data['transaction_summary']['data']['status']=='failed')
		{
			return $hospitalitySuiteTransaction;
		}
		$this->service->saveBookedHospitalitySuiteDietaryOption($hospitalitySuiteTransaction->id, $hospitalitySuitsObj);

		$this->service->saveBookedHospitalitySuite($hospitalitySuiteTransaction->id, $data, $bookedSeatCount);
		$this->service->updateReceiptNumberOfHospitalitySuite($consumer, $hospitalitySuiteTransaction);

		$hospitalityPrice = $hospitalitySuiteTransaction->price;
		if($hospitalitySuite->rewards_percentage_override !== null && $hospitalitySuite->rewards_percentage_override !== '') {
			$hospitalityRewardPercentage = $hospitalitySuite->rewards_percentage_override;
		} else {
			$clubLoyaltyPointData = ClubLoyaltyPointSetting::where('club_id', $consumer->club_id)->first();
			$hospitalityRewardPercentage = $clubLoyaltyPointData->hospitality_reward_percentage;
		}
		$earnedLoyaltyPoints = $hospitalityPrice * $hospitalityRewardPercentage;

		$loyaltyRewardPointHistory = $this->loyaltyRewardPointHistoryService->createLoyaltyRewardPointHistory($consumer, $hospitalitySuiteTransaction->id, $earnedLoyaltyPoints, 'hospitality');
		//$hospitalitySuiteTransaction = HospitalitySuiteTransaction::find(1);
        $clubAdmins = $this->userService->clubAdmin($consumer->club_id);
		$superAdmins = $this->userService->superAdmin();
		SendHospitalitySuiteTransactionEmail::dispatch($hospitalitySuiteTransaction,$consumer,$clubAdmins,$superAdmins)->onQueue(config('fanslive.TRANSACTION_EMAILS'));
		return new HospitalitySuiteTransactionResource($hospitalitySuiteTransaction);
	}

	/**
     * Validate hospitality Payment
     */
    public function validateHospitalityTicketPayment(ValidatePaymentRequest $request)
    {
    	return $this->service->validateHospitalityTicketPayment($request->all());
    }
}
