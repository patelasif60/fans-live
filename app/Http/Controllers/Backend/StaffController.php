<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Staff\StoreRequest;
use App\Http\Requests\User\Staff\UpdateRequest;
use App\Models\Club;
use App\Models\User;
use App\Models\Staff;
use App\Models\ProductAndLoyaltyRewardTransactionCollection;
use App\Models\BookedTicketScanStatus;
use App\Services\StaffService;
use Illuminate\Http\Request;
use JavaScript;

class StaffController extends Controller
{
    /**
     * The staff user service.
     *
     * @var StaffService
     */
    protected $service;

    public function __construct(StaffService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($club=null)
    {
        $staffUsers = User::all();
        $clubs = Club::all();
        JavaScript::put(['clubdata' => null]);
        if($club)
        {
            $clubData=Club::where('slug',$club)->get()->first();
            JavaScript::put(['clubdata' => $clubData]);
            return view('backend.users.staff.index', compact('staffUsers', 'clubs','clubData'));
        }

        return view('backend.users.staff.index', compact('staffUsers', 'clubs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($club=null)
    {
        $status = config('fanslive.USER_STATUS');
        $clubs = Club::all();
        JavaScript::put(['clubdata' => null]);
        if($club)
        {
            $clubData=Club::where('slug',$club)->get()->first();
            JavaScript::put(['clubdata' => $clubData]);
            return view('backend.users.staff.create', compact('status', 'clubs','clubData'));
        }

        return view('backend.users.staff.create', compact('status', 'clubs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request,$club=null)
    {
        $staffUser = $this->service->create(
            $request->all()
        );

        if ($staffUser) {
            flash('Staff APP user created successfully')->success();
        } else {
            flash('Staff APP user could not be created. Please try again.')->error();
        }

        if($club)
        {
            return redirect()->route('backend.staff.club.index',['club'=>$club]);
        }
        return redirect()->route('backend.staff.index');
        
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
        $staff = $this->service->getStaffDetail($userId);
        $user =  $staff->user;
        $selectedClub = $staff->club;
        $clubs = Club::all();
        JavaScript::put(['clubdata' => null]);
        if($club)
        {
            $clubData=Club::where('slug',$club)->get()->first();
            JavaScript::put(['clubdata' => $clubData]);
            return view('backend.users.staff.edit', compact('user', 'status', 'clubs','clubData','selectedClub'));
        }
        return view('backend.users.staff.edit', compact('user', 'status', 'clubs', 'selectedClub'));
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
        $staffUser = $this->service->update(
            $user,
            $request->all()
        );

        if ($staffUser) {
            flash('Staff APP user updated successfully')->success();
        } else {
            flash('Staff APP user could not be updated. Please try again.')->error();
        }
        if($club)
        {
            return redirect()->route('backend.staff.club.index', ['club' =>$club]);    
        }

        return redirect()->route('backend.staff.index');
    }

    /**
     * Remove the specified staff user.
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
        $staff = Staff::where('user_id', $user->id)->first();
        if ($staff) {
            $productAndLoyaltyRewardTransactionCollection = ProductAndLoyaltyRewardTransactionCollection::where('staff_id', $staff->id)->get();
            $bookedTicketScanStatus = BookedTicketScanStatus::where('staff_id', $staff->id)->get();
            if (count($productAndLoyaltyRewardTransactionCollection) == 0 && count($bookedTicketScanStatus) == 0) {
                if ($user->delete()) {
                    return response()->json(['status'=>'success', 'message'=>'Staff APP user deleted successfully.']);
                } else {
                    return response()->json(['status'=>'error', 'message'=>'Staff APP user could not be deleted. Please try again.']);
                }
            } else {
                return response()->json(['status'=>'error', 'message'=>'This staff APP user cannot be deleted as transactions have been completed using this staff APP user.']);
            }
        } else {
            return response()->json(['status'=>'error', 'message'=>'Staff APP user not found.']);
        }
    }

    /**
     * Get staff user data.
     *
     * @return \Illuminate\Http\Response
     */
    public function getStaffAPPUserData(Request $request)
    {
        $staffUsers = $this->service->getData(
            $request->all()
        );

        return $staffUsers;
    }
}
