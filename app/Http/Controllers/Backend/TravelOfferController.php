<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\TravelOffer\UpdateRequest;
use App\Models\TravelOffer;
use App\Services\TravelOfferService;
use Illuminate\Http\Request;
use JavaScript;
class TravelOfferController extends Controller
{
    public function __construct(TravelOfferService $service)
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
		return view('backend.traveloffers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $travelOffersStatus = config('fanslive.PUBLISH_STATUS');

        return view('backend.traveloffers.create', compact('travelOffersStatus'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $club
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $club)
    {
        $clubId = getClubIdBySlug($club);
        $travelOffers = $this->service->create(
            $clubId,
            auth()->user(),
            $request->all()
        );

        if ($travelOffers) {
            flash('Travel offers created successfully')->success();
        } else {
            flash('Travel offers could not be created. Please try again.')->error();
        }

        return redirect()->route('backend.traveloffers.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $clubId, TravelOffer $travelOffers)
    {
        $travelOffersStatus = config('fanslive.PUBLISH_STATUS');

        return view('backend.traveloffers.edit', compact('travelOffersStatus', 'travelOffers'));
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
    public function update(UpdateRequest $request, $clubId, TravelOffer $travelOffers)
    {
        $travelOffersToUpdate = $this->service->update(
            auth()->user(),
            $travelOffers,
            $request->all()
        );

        if ($travelOffersToUpdate) {
            flash('Travel offers updated successfully')->success();
        } else {
            flash('Travel offers could not be updated. Please try again.')->error();
        }

        return redirect()->route('backend.traveloffers.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($clubId, TravelOffer $travelOffers)
    {
        $this->service->deleteLogo($travelOffers);
        if ($travelOffers->delete()) {
            flash('Travel offers deleted successfully')->success();
        } else {
            flash('Travel offers could not be deleted. Please try again.')->error();
        }

        return redirect()->route('backend.traveloffers.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Get Travel offers list data.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTraveloffersData(Request $request, $club)
    {
        $clubId = getClubIdBySlug($club);
        $travelOffersList = $this->service->getData(
            $clubId,
            $request->all()
        );

        return $travelOffersList;
    }
}
