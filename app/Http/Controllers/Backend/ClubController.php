<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Club\StoreRequest;
use App\Http\Requests\Club\UpdateRequest;
use App\Models\Club;
use App\Models\ClubBankDetail;
use App\Models\ClubCategory;
use App\Models\Competition;
use App\Services\ClubService;
use Illuminate\Http\Request;
use JavaScript;
use Timezonelist;
class ClubController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ClubService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of clubs.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = ClubCategory::all();

        return view('backend.clubs.index', compact('categories'));
    }

    /**
     * Show the form for creating a new club.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ClubCategory::all();
        $clubStatus = config('fanslive.PUBLISH_STATUS');
        $competitions = Competition::all();
		$currencyList = config('fanslive.CURRENCY_TYPE');

		$selected = null;
		$formAttributes = array('class' => 'js-select2 form-control', 'style' => 'float:left;');

		$timeZone = Timezonelist::create('time_zone', $selected, $formAttributes);
        JavaScript::put([
            'competitions' => $competitions,
        ]);

        return view('backend.clubs.create', compact('clubStatus', 'categories', 'competitions','currencyList','timeZone'));
    }

    /**
     * Store a newly created club.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $club = $this->service->create(
            auth()->user(),
            $request->all()
        );

        if ($club) {
            flash('Club created successfully')->success();
        } else {
            flash('Club could not be created. Please try again.')->error();
        }

        return redirect()->route('backend.club.index');
    }

    /**
     * Show the form for editing a club.
     *
     * @param  $clubId
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $clubId)
    {

        $competitions = Competition::all();
        $club = $this->service->getClubDetail($clubId);
		$clubBankDetail = $this->service->getClubBankDetail($clubId);
        $categories = ClubCategory::all();
        $clubStatus = config('fanslive.PUBLISH_STATUS');
		$currencyList = config('fanslive.CURRENCY_TYPE');

		$selected = $club->time_zone;
		$formAttributes = array('class' => 'js-select2 form-control', 'style' => 'float:left;');
		$timeZone = Timezonelist::create('time_zone', $selected, $formAttributes);

		JavaScript::put([
            'competitions'     => $competitions,
            'clubCompetitions' => $club->competitions()->get(),
            'clubCurrency' => $club->currency,
        ]);

        return view('backend.clubs.edit', compact('clubStatus', 'club', 'categories', 'clubBankDetail', 'currencyList', 'timeZone'));
    }

    /**
     * Update the specified club.
     *
     * @param \Illuminate\Http\Request $request
     * @param  $clubId
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Club $clubId)
    {
        $clubToUpdate = $this->service->update(
            auth()->user(),
            $clubId,
            $request->all()
        );

        if ($clubToUpdate) {
            flash('Club updated successfully')->success();
        } else {
            flash('Club could not be updated. Please try again.')->error();
        }

        return redirect()->route('backend.club.index');
    }

    /**
     * Remove the specified club.
     *
     * @param  $clubId
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Club $clubId)
    {
        $clubLogoToDelete = $this->service->deleteLogo(
            $clubId
        );

        if ($clubLogoToDelete && $clubId->delete()) {
            flash('Club deleted successfully')->success();
        } elseif (!$clubLogoToDelete && $clubId->delete()) {
            flash('Club deleted successfully')->success();
        } else {
            flash('Club could not be deleted. Please try again.')->error();
        }

        return redirect()->route('backend.club.index');
    }

    /**
     * Get club data.
     *
     * @return \Illuminate\Http\Response
     */
    public function getClubData(Request $request)
    {
        $clubs = $this->service->getData(
            $request->all()
        );

        return $clubs;
    }
}
