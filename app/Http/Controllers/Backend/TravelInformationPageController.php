<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\TravelInformationPage\StoreRequest;
use App\Http\Requests\TravelInformationPage\UpdateRequest;
use App\Models\TravelInformationPage;
use App\Services\TravelInformationPageService;
use Illuminate\Http\Request;
use JavaScript;

class TravelInformationPageController extends Controller
{
	/**
	 * A Travelinformation service.
	 *
	 * @var travelInformationPageService
	 */
	protected $travelInformationPageService;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(TravelInformationPageService $travelInformationPageService)
	{
		$this->middleware('auth');
		$this->travelInformationPageService = $travelInformationPageService;
	}

	/**
	 * Display a listing of travel information page.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		JavaScript::put([
			'dateTimeCmsFormat' => config('fanslive.DATE_TIME_CMS_FORMAT.js'),
		]);
		return view('backend.travelinformationpages.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$travelInformationPageStatus = config('fanslive.PUBLISH_STATUS');

		return view('backend.travelinformationpages.create', compact('travelInformationPageStatus'));
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
		$travelInformationPage = $this->travelInformationPageService->create(
			$clubId,
			auth()->user(),
			$request->all()
		);
		if ($travelInformationPage) {
			flash('Travel information page created successfully')->success();
		} else {
			flash('Travel information page could not be created. Please try again.')->error();
		}

		return redirect()->route('backend.travelinformationpages.index', ['club' => app()->request->route('club')]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $request
	 * @param int $clubId
	 * @param int $travelInformationPage
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Request $request, $clubId, TravelInformationPage $travelInformationPage)
	{
		$traveInfo = [];
		if ($travelInformationPage->travelInformationPageContent) {
			foreach ($travelInformationPage->travelInformationPageContent as $key => $value) {
				$traveInfo[$key]['id'] = $value->id;
				$traveInfo[$key]['title'] = $value->title;
				$traveInfo[$key]['description'] = $value->content;
			}
		}
		Javascript::put([
			'traveInfo' => $traveInfo,
		]);

		$travelInformationPageStatus = config('fanslive.PUBLISH_STATUS');

		return view('backend.travelinformationpages.edit', compact('travelInformationPageStatus', 'travelInformationPage'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param int $request
	 * @param int $clubId
	 * @param int $travelInformationPage
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(UpdateRequest $request, $clubId, TravelInformationpage $travelInformationPage)
	{
		$travelInformatioPageToUpdate = $this->travelInformationPageService->update(
			auth()->user(),
			$travelInformationPage,
			$request->all()
		);

		if ($travelInformatioPageToUpdate) {
			flash('Travel information page updated successfully')->success();
		} else {
			flash('Travel information page could not be updated. Please try again.')->error();
		}

		return redirect()->route('backend.travelinformationpages.index', ['club' => app()->request->route('club')]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $clubId
	 * @param int $travelInformationPage
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($clubId, TravelInformationPage $travelInformationPage)
	{
		$traveInformationPhotoDelete = $this->travelInformationPageService->deleteLogo($travelInformationPage);

		if ($travelInformationPage->delete()) {
			flash('Travel information page deleted successfully')->success();
		} else {
			flash('Travel information page could not be deleted. Please try again.')->error();
		}

		return redirect()->route('backend.travelinformationpages.index', ['club' => app()->request->route('club')]);
	}

	/**
	 * Get Travel information list data.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getTravelInformationPageData(Request $request, $club)
	{
		$clubId = getClubIdBySlug($club);
		$travelInformationList = $this->travelInformationPageService->getTravelInformationPageData(
			$clubId,
			$request->all()
		);

		return $travelInformationList;
	}
}
