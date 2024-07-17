<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\TravelWarning\StoreRequest;
use App\Http\Requests\TravelWarning\UpdateRequest;
use App\Models\TravelWarning;
use App\Services\TravelWarningService;
use Illuminate\Http\Request;
use JavaScript;

class TravelWarningController extends Controller
{
    public function __construct(TravelWarningService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		JavaScript::put([
			'dateTimeCmsFormat' => config('fanslive.DATE_TIME_CMS_FORMAT.js'),
		]);
        return view('backend.travelwarnings.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $travelWarningsColors = config('fanslive.WARNING_COLORS');
        $travelWarningsStatus = config('fanslive.PUBLISH_STATUS');

        return view('backend.travelwarnings.create', compact('travelWarningsColors', 'travelWarningsStatus'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $club
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, $club)
    {
        $clubId = getClubIdBySlug($club);
        $travelWarnings = $this->service->create(
            $clubId,
            auth()->user(),
            $request->all()
        );

        if ($travelWarnings) {
            flash('Travel warning created successfully')->success();
        } else {
            flash('Travel warning could not be created. Please try again.')->error();
        }

        return redirect()->route('backend.travelwarnings.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $clubId, TravelWarning $travelWarning)
    {
        $travelWarningsColors = config('fanslive.WARNING_COLORS');
        $travelWarningsStatus = config('fanslive.PUBLISH_STATUS');

        return view('backend.travelwarnings.edit', compact('travelWarningsColors', 'travelWarning', 'travelWarningsStatus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  $clubId
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $clubId, TravelWarning $travelWarning)
    {
        $travelWarningToUpdate = $this->service->update(
            auth()->user(),
            $travelWarning,
            $request->all()
        );

        if ($travelWarningToUpdate) {
            flash('Travel warning updated successfully')->success();
        } else {
            flash('Travel warning could not be updated. Please try again.')->error();
        }

        return redirect()->route('backend.travelwarnings.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($clubId, TravelWarning $travelWarning)
    {
        if ($travelWarning->delete()) {
            flash('Travel warning deleted successfully')->success();
        } else {
            flash('Travel warning could not be deleted. Please try again.')->error();
        }

        return redirect()->route('backend.travelwarnings.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Get Travel warnings list data.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTravelWarningsData(Request $request, $club)
    {
        $clubId = getClubIdBySlug($club);
        $travelWarningsList = $this->service->getData(
            $clubId,
            $request->all()
        );

        return $travelWarningsList;
    }
}
