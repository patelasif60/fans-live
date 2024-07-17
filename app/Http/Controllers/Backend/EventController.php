<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\StoreRequest;
use App\Http\Requests\Event\UpdateRequest;
use App\Models\Event;
use App\Models\EventTransaction;
use App\Services\EventService;
use App\Services\MembershipPackageService;
use Illuminate\Http\Request;
use JavaScript;
use App\Models\Club;
/**
 * Event Controller class to handle request.
 */
class EventController extends Controller
{
    /**
     * The Event service instance.
     *
     * @var service
     */
    public function __construct(EventService $service, MembershipPackageService $membershipPackageService)
    {
        $this->service = $service;
        $this->membershipPackageService = $membershipPackageService;
    }

    /**
     * Display a listing of the resource.
     * @param $club
     * @return \Illuminate\Http\Response
     */
    public function index($club)
    {
		$club = Club::where('slug',$club)->first();
		$currencySymbol = config('fanslive.CURRENCY_SYMBOL');
		$currencyIcon =  $currencySymbol[$club->currency];

		JavaScript::put([
			'dateTimeCmsFormat' => config('fanslive.DATE_TIME_CMS_FORMAT.js'),
		]);
		return view('backend.events.index',compact('currencyIcon'));
    }

    /**
     * Show the form for creating a event resource.
     *
     * @param $club
     *
     * @return \Illuminate\Http\Response
     */
    public function create($club)
    {
		$club = Club::where('slug', $club)->first();
		$currencySymbol = config('fanslive.CURRENCY_SYMBOL');

		$membershipPackageList = $this->membershipPackageService->getMembershipPackageList($club->id);

        $eventStatus = config('fanslive.PUBLISH_STATUS');

        return view('backend.events.create', compact('eventStatus', 'membershipPackageList', 'club', 'currencySymbol'));
    }

    /**
     * Store a event created resource in storage.
     *
     * @param \App\Http\Requests\Event\StoreRequest $request
     * @param  $club
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, $club)
    {
        $clubId = getClubIdBySlug($club);
        $event = $this->service->create(
            $clubId,
            auth()->user(),
            $request->all()
        );

        if ($event) {
            flash('Event created successfully')->success();
        } else {
            flash('Event could not be created. Please try again.')->error();
        }

        return redirect()->route('backend.event.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param  $clubId
     * @param  $event
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $clubId, Event $event)
    {
		$club = Club::where('slug', $clubId)->first();
		$currencySymbol = config('fanslive.CURRENCY_SYMBOL');
        // Get all membership package
        $membershipPackageList = $this->membershipPackageService->getMembershipPackageList($club->id);

        // Get only event membership package
        $eventPackage = $this->service->getEventMembershipPackage($event);

        $eventStatus = config('fanslive.PUBLISH_STATUS');

        return view('backend.events.edit', compact('eventStatus', 'event', 'membershipPackageList', 'eventPackage', 'club', 'currencySymbol'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Event\UpdateRequest $request
     * @param  $clubId
     * @param  $event
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $clubId, Event $event)
    {
        $eventToUpdate = $this->service->update(
            auth()->user(),
            $event,
            $request->all()
        );

        if ($eventToUpdate) {
            flash('Event updated successfully')->success();
        } else {
            flash('Event could not be updated. Please try again.')->error();
        }

        return redirect()->route('backend.event.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $clubId
     * @param  $event
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($clubId, Event $event)
    {
        $eventTransactions = EventTransaction::where('event_id',$event->id)->where('status','successful')->get();
        if (count($eventTransactions) == 0) {
            $eventLogoToDelete = $this->service->deleteLogo($event);
            if ($event->delete()) {
                return response()->json(['status'=>'success', 'message'=>'Event deleted successfully']);
            } else {
                return response()->json(['status'=>'error', 'message'=>'Event could not be deleted. Please try again.']);
            }
        } else {
            return response()->json(['status'=>'error', 'message'=>'This event cannot be deleted as transactions have been completed using this event.']);
        }
    }

    /**
     * Get Event list data.
     *
     * @param \Illuminate\Http\Request $request
     * @param  $clubId
     *
     * @return \Illuminate\Http\Response
     */
    public function getEventData(Request $request, $club)
    {
        $clubId = getClubIdBySlug($club);
        $eventList = $this->service->getData(
            $clubId,
            $request->all()
        );

        return $eventList;
    }

    /**
     * unset class instance.
     */
    public function __destruct()
    {
        unset($this->service);
    }
}
