<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CollectionPoint\StoreRequest;
use App\Http\Requests\CollectionPoint\UpdateRequest;
use App\Models\CollectionPoint;
use App\Models\StadiumBlock;
use App\Models\StadiumGeneralSetting;
use App\Models\StadiumEntrance;
use App\Services\CollectionPointService;
use App\Services\StadiumBlockService;
use Illuminate\Http\Request;
use JavaScript;

/**
 * Collection Point Controller class to handle request.
 */
class CollectionPointController extends Controller
{
	/**
	 * The collection point service instance.
	 *
	 * @var service
	 */
	public function __construct(CollectionPointService $service, StadiumBlockService $stadiumBlockService)
	{
		$this->service = $service;
		$this->stadiumBlockService = $stadiumBlockService;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return view('backend.collectionpoints.index');
	}

	/**
	 * Show the form for creating a collection point resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create($club)
	{
		$clubId = getClubIdBySlug($club);
		$stadiumGeneralSetting = StadiumGeneralSetting::where('club_id', $clubId)->first();
		$collectionPointStatus = config('fanslive.PUBLISH_STATUS');
		$stadiumBlockList = $this->stadiumBlockService->getBlocks($clubId);

		JavaScript::put([
            'competitions' => ($stadiumGeneralSetting) ? $stadiumGeneralSetting->is_using_allocated_seating : 0,
            'is_using_allocated_seating' => $stadiumGeneralSetting->is_using_allocated_seating
        ]);

		return view('backend.collectionpoints.create', compact('collectionPointStatus', 'stadiumBlockList'));
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
		$collectionPoints = $this->service->create(
			$clubId,
			auth()->user(),
			$request->all()
		);

		if ($collectionPoints) {
			flash('Collection point created successfully')->success();
		} else {
			flash('Collection point could not be created. Please try again.')->error();
		}

		return redirect()->route('backend.collectionpoint.index', ['club' => app()->request->route('club')]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param $club
	 * @param $collectionPoint
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Request $request, $club, CollectionPoint $collectionpoint)
	{

		$clubId = getClubIdBySlug($club);
		$stadiumGeneralSetting = StadiumGeneralSetting::where('club_id', $clubId)->first();
		$collectionPointStatus = config('fanslive.PUBLISH_STATUS');
		$stadiumBlocks = $this->stadiumBlockService->getBlocks($clubId);
		$stadiumEntrance = StadiumEntrance::with(['stadiumEntranceBlocks'])->where('club_id', $clubId)->get();
		$stadiumEntrance = $this->service->prepareBlocksData($stadiumEntrance, $stadiumBlocks);
		$selectedStadiumBlocks = $collectionpoint->collectionPointStadiumBlocks->pluck('stadium_block_id')->toArray();

		JavaScript::put([
            'competitions' => ($stadiumGeneralSetting) ? $stadiumGeneralSetting->is_using_allocated_seating : 0,
            'is_using_allocated_seating' => $stadiumGeneralSetting->is_using_allocated_seating
        ]);

		return view('backend.collectionpoints.edit', compact('collectionpoint', 'collectionPointStatus', 'stadiumEntrance', 'stadiumBlocks','selectedStadiumBlocks'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param  $clubId
	 * @param $collectionpoint
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(UpdateRequest $request, $clubId, CollectionPoint $collectionpoint)
	{
		//dd($request);
		$collectionPointToUpdate = $this->service->update(
			auth()->user(),
			$collectionpoint,
			$request->all()
		);

		if ($collectionPointToUpdate) {
			flash('Collection Point updated successfully')->success();
		} else {
			flash('Collection Point could not be updated. Please try again.')->error();
		}

		return redirect()->route('backend.collectionpoint.index', ['club' => app()->request->route('club')]);
	}


	/**
	 * Get Travel offers list data.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getProductData(Request $request, $club)
	{
		$clubId = getClubIdBySlug($club);
		$collectionPointsList = $this->service->getData(
			$clubId,
			$request->all()
		);
		return $collectionPointsList;
	}

	/**
	 * Delete the specified resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param  $clubId
	 * @param $collectionpoint
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request, $club, CollectionPoint $collectionpoint)
	{
		if ($collectionpoint->delete()) {
			$collectionpoint->products()->detach();
			flash('Collection Point deleted successfully')->success();
		} else {
			flash('Collection Point could not be deleted. Please try again.')->error();
		}

		return redirect()->route('backend.collectionpoint.index', ['club' => app()->request->route('club')]);
	}

}
