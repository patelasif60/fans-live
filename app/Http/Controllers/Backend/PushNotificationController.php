<?php

namespace App\Http\Controllers\Backend;

use JavaScript;
use Illuminate\Http\Request;
use App\Services\MatchService;
use App\Models\PushNotification;
use App\Http\Controllers\Controller;
use App\Services\PushNotificationService;
use App\Services\MembershipPackageService;
use App\Http\Requests\PushNotification\StoreRequest;
use App\Http\Requests\PushNotification\UpdateRequest;

class PushNotificationController extends Controller
{
    /**
     * A push notification service.
     *
     * @var pushnotificationService
     */
    protected $pushnotificationService;

    /**
     * A match service.
     *
     * @var MatchService
     */
    protected $matchService;

    /**
     * A Membership Package service.
     *
     * @var membershipPackageService
     */
    protected $membershipPackageService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PushNotificationService $pushnotificationService, MatchService $matchService, MembershipPackageService $membershipPackageService)
    {
        $this->middleware('auth');
        $this->pushnotificationService = $pushnotificationService;
        $this->matchService = $matchService;
        $this->membershipPackageService = $membershipPackageService;
    }

    /**
     * Display a listing of poll.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($clubId)
    {
        $club = getClubIdBySlug($clubId);
		return view('backend.pushnotifications.index', compact('club'));
    }

    /**
     * Show the form for creating a new push notification.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($club)
    {
    	$categories = config('fanslive.SWIPE_ACTION_CATEGORIES');
        $clubId = getClubIdBySlug($club);
        $matches = $this->matchService->getCurrentAndFutureMatches($clubId);
        $memberships = $this->membershipPackageService->getMembershipPackageForCurrentClub($clubId);
        return view('backend.pushnotifications.create', compact('categories', 'matches', 'memberships'));
    }

    /**
     * Show the form for creating a new push notification.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSwipeActionItems(Request $request, $clubId)
    {
        $club = getClubIdBySlug($clubId);
    	$data = $request->all();
        $items = $this->pushnotificationService->getSwipeActionItems($club, $data);
        return $items;
    }

    /**
     * Store a newly created push notification.
     *
     * @param $club
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, $club)
    {
        $clubId = getClubIdBySlug($club);
        $pushNotification = $this->pushnotificationService->create(
            $clubId,
            auth()->user(),
            $request->all()
        );
        if ($pushNotification) {
            flash('Push notification created successfully')->success();
        } else {
            flash('Push notification could not be created. Please try again.')->error();
        }

        return redirect()->route('backend.pushnotification.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $clubId
     * @param $pushnotification
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $clubId, PushNotification $pushnotification)
    {
        $categories = config('fanslive.SWIPE_ACTION_CATEGORIES');
        $clubId = getClubIdBySlug($clubId);
        $matches = $this->matchService->getCurrentAndFutureMatches($clubId);
        $memberships = $this->membershipPackageService->getMembershipPackageForCurrentClub($clubId);
        JavaScript::put([
            'pushnotificationSwipeActionItem' => $pushnotification->swipe_action_item,
        ]);
        return view('backend.pushnotifications.edit', compact('pushnotification', 'categories', 'matches', 'memberships'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $clubId
     * @param $pushnotification
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $clubId, PushNotification $pushnotification)
    {
        $pushNotificationUpdate = $this->pushnotificationService->update(
            auth()->user(),
            $pushnotification,
            $request->all()
        );

        if ($pushNotificationUpdate) {
            flash('Push notification updated successfully')->success();
        } else {
            flash('Push notification could not be updated. Please try again.')->error();
        }

        return redirect()->route('backend.pushnotification.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Remove the specified push notification.
     *
     * @param  $clubId
     * @param  $pushnotification
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($clubId, PushNotification $pushnotification)
    {
        if ($pushnotification->delete()) {
            flash('Push notification deleted successfully')->success();
        } else {
            flash('Push notification could not be deleted. Please try again.')->error();
        }

        return redirect()->route('backend.pushnotification.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Get push notification list data.
     *
     * @param  $club
     *
     * @return \Illuminate\Http\Response
     */
    public function getPushnotificationData(Request $request, $club)
    {
        $clubId = getClubIdBySlug($club);
        $pushNotificationList = $this->pushnotificationService->getData(
            $clubId,
            $request->all()
        );

        return $pushNotificationList;
    }
}
