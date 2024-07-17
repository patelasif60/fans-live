<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Consumer\StoreRequest;
use App\Http\Requests\User\Consumer\UpdateRequest;
use App\Models\Club;
use App\Models\User;
use App\Models\Consumer;
use App\Models\ProductTransaction;
use App\Models\TicketTransaction;
use App\Models\EventTransaction;
use App\Models\HospitalitySuiteTransaction;
use App\Models\ConsumerMembershipPackage;
use App\Services\ConsumerService;
use App\Services\LoyaltyRewardPointHistoryService;
use Illuminate\Http\Request;
use JavaScript;
use Timezonelist;

class ConsumerController extends Controller
{
    /**
     * A consumer user service.
     *
     * @var service
     */
    protected $service;
    protected $loyaltyRewardPointHistoryService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ConsumerService $service, LoyaltyRewardPointHistoryService $loyaltyRewardPointHistoryService)
    {
        $this->service = $service;
        $this->loyaltyRewardPointHistoryService = $loyaltyRewardPointHistoryService;
    }

    /**
     * Display a listing of a consumer users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($club=null)
    {
        $appUsers = User::all();
        $clubs = Club::all();
        JavaScript::put(['clubdata' => null]);
        if($club)
        {
            $clubData=Club::where('slug',$club)->get()->first();
            JavaScript::put(['clubdata' => $clubData]);
            return view('backend.users.consumer.index', compact('appUsers', 'clubs','clubData'));
        }

        return view('backend.users.consumer.index', compact('appUsers', 'clubs'));
    }

    /**
     * Show the form for creating new consumer user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($club=null)
    {
        $status = config('fanslive.USER_STATUS');
        $clubs = Club::where('status', 'Published')->get();
        JavaScript::put(['clubdata' => null]);

		$selected = null;
		$formAttributes = array('class' => 'js-select2 form-control', 'style' => 'float:left;');
		$timeZone = Timezonelist::create('time_zone', $selected, $formAttributes);

		if($club)
        {
            $clubData=Club::where('slug',$club)->get()->first();
            JavaScript::put(['clubdata' => $clubData]);
            return view('backend.users.consumer.create', compact('status', 'clubs','clubData','timeZone'));
        }

        return view('backend.users.consumer.create', compact('status', 'clubs', 'timeZone'));
    }

    /**
     * Store a newly created consumer user.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request,$club=null)
    {
        $consumerUser = $this->service->create(
            $request->all()
        );

        if ($consumerUser) {
            flash('APP user created successfully')->success();
        } else {
            flash('APP user could not be created. Please try again.')->error();
        }
        if($club)
        {
            return redirect()->route('backend.consumer.club.index',['club'=>$club]);
        }

        return redirect()->route('backend.consumer.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $userId,$club=null)
    {
        $temp = $userId;
        if($club!=null)
        {
            $userId = $club;
            $club = $temp;
        }
        $status = config('fanslive.USER_STATUS');
        $user = $this->service->getConsumerDetail($userId);
        $userLoyaltyRewardPointBalance = $this->loyaltyRewardPointHistoryService->getConsumerLoyaltyRewardPointBalance($userId);
        $clubs = Club::where('status', 'Published')->get();
        JavaScript::put(['clubdata' => null]);

		$selected = $user->time_zone;
		$formAttributes = array('class' => 'js-select2 form-control', 'style' => 'float:left;');
		$timeZone = Timezonelist::create('time_zone', $selected, $formAttributes);

        if($club)
        {
            $clubData=Club::where('slug',$club)->get()->first();
            JavaScript::put(['clubdata' => $clubData]);
            return view('backend.users.consumer.edit', compact('user', 'status', 'clubs','clubData','timeZone'));
        }
        return view('backend.users.consumer.edit', compact('user', 'status', 'clubs','userLoyaltyRewardPointBalance','timeZone'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request,$club,User $user=null)
    {
        $temp = $club;
        if($user==null)
        {
            $club = $user;
            $user = $temp;
        }
        $consumerUser = $this->service->update(
            $user,
            $request->all()
        );

        if ($consumerUser) {
            flash('APP user updated successfully')->success();
        } else {
            flash('APP user could not be updated. Please try again.')->error();
        }
        if($club)
        {
            return redirect()->route('backend.consumer.club.index', ['club' =>$club]);
        }

        return redirect()->route('backend.consumer.index');
    }

    /**
     * Remove the specified consumer.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($club,User $user=null)
    {
        if($user==null)
        {
            $user=$club;
            $club=null;
        }
        $consumer = Consumer::where('user_id', $user->id)->first();
        if ($consumer) {
            $ticketTransactions = TicketTransaction::where('consumer_id', $consumer->id)->where('status', 'successful')->get();
            $eventTransactions = EventTransaction::where('consumer_id', $consumer->id)->where('status', 'successful')->get();
            $hospitalitySuiteTransactions = HospitalitySuiteTransaction::where('consumer_id', $consumer->id)->where('status', 'successful')->get();
            $consumerMembershipPackages = ConsumerMembershipPackage::where('consumer_id', $consumer->id)->where('status', 'successful')->get();
            $productTransactions = ProductTransaction::where('consumer_id', $consumer->id)->where('status', 'successful')->get();
            if ((count($ticketTransactions) == 0) && (count($eventTransactions) == 0) && (count($hospitalitySuiteTransactions) == 0) && (count($consumerMembershipPackages) == 0) && (count($productTransactions) == 0)) {
                if ($user->delete()) {
                    return response()->json(['status'=>'success', 'message'=>'APP user deleted successfully.']);
                } else {
                    return response()->json(['status'=>'error', 'message'=>'APP user could not be deleted. Please try again.']);
                }
            } else {
                return response()->json(['status'=>'error', 'message'=>'This event cannot be deleted as transactions have been completed using this event.']);
            }
        } else {
            return response()->json(['status'=>'error', 'message'=>'APP user not found.']);
        }
    }

    /**
     * Get consumer user data.
     *
     * @return \Illuminate\Http\Response
     */
    public function getConsumerAPPUserData(Request $request)
    {
        $consumerUsers = $this->service->getData(
            $request->all()
        );

        return $consumerUsers;
    }
}
