<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MembershipPackage\StoreRequest;
use App\Http\Requests\MembershipPackage\UpdateRequest;
use App\Models\Club;
use App\Models\MembershipPackage;
use App\Models\ConsumerMembershipPackage;
use App\Services\MembershipPackageService;
use Illuminate\Http\Request;

class MembershipPackageController extends Controller
{
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
	public function __construct(MembershipPackageService $membershipPackageService)
	{
		$this->middleware('auth');
		$this->membershipPackageService = $membershipPackageService;
	}

	/**
	 * Destory/Unset object variables.
	 *
	 * @return void
	 */
	public function __destruct()
	{
		unset($this->membershipPackageService);
	}

	/**
	 * Display a listing of membership package.
	 *
	 * @param $club
	 * @return \Illuminate\Http\Response
	 */
	public function index($club)
	{
		$club = Club::where('slug',$club)->first();
		$currencySymbol = config('fanslive.CURRENCY_SYMBOL');
		$currencyIcon =  $currencySymbol[$club->currency];

		return view('backend.membershippackages.index',compact('currencyIcon'));
	}

	/**
	 * Show the form for creating a new membership package.
	 *
	 * @param $club
	 * @return \Illuminate\Http\Response
	 */
	public function create($club)
	{
		$club = Club::where('slug', $club)->first();
		$currencySymbol = config('fanslive.CURRENCY_SYMBOL');
		$membershipPackageStatus = config('fanslive.PUBLISH_STATUS');

		return view('backend.membershippackages.create', compact('membershipPackageStatus', 'club', 'currencySymbol'));
	}

	/**
	 * Store a newly created membership package.
	 *
	 * @param $club
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(StoreRequest $request, $club)
	{
		$clubId = getClubIdBySlug($club);
		$membershipPackage = $this->membershipPackageService->create(
			$clubId,
			auth()->user(),
			$request->all()
		);
		if ($membershipPackage) {
			flash('Membership package created successfully')->success();
		} else {
			flash('Membership package could not be created. Please try again.')->error();
		}

		return redirect()->route('backend.membershippackages.index', ['club' => app()->request->route('club')]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $request
	 * @param int $clubId
	 * @param int $membershipPackage
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Request $request, $clubId, MembershipPackage $membershipPackage)
	{
		$membershipPackageStatus = config('fanslive.PUBLISH_STATUS');
		$club = Club::where('slug', $clubId)->first();
		$currencySymbol = config('fanslive.CURRENCY_SYMBOL');

		return view('backend.membershippackages.edit', compact('membershipPackage', 'membershipPackageStatus', 'club', 'currencySymbol'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param int $clubId
	 * @param int $membershipPackage
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(UpdateRequest $request, $clubId, MembershipPackage $membershipPackage)
	{
		$membershipPackageToUpdate = $this->membershipPackageService->update(
			auth()->user(),
			$membershipPackage,
			$request->all()
		);

		if ($membershipPackageToUpdate) {
			flash('Membership package updated successfully')->success();
		} else {
			flash('Membership package could not be updated. Please try again.')->error();
		}

		return redirect()->route('backend.membershippackages.index', ['club' => app()->request->route('club')]);
	}

	/**
	 * Remove the specified membership package.
	 *
	 * @param int $membershipPackage
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($clubId, MembershipPackage $membershipPackage)
	{
		$consumerMembershipPackages = ConsumerMembershipPackage::where('membership_package_id', $membershipPackage->id)->where('status', 'successful')->get();
		if (count($consumerMembershipPackages) == 0) {
			$this->membershipPackageService->deleteIcon($membershipPackage);
			if ($membershipPackage->delete()) {
                return response()->json(['status'=>'success', 'message'=>'Membership package deleted successfully']);
			} else {
                return response()->json(['status'=>'error', 'message'=>'Membership package could not be deleted. Please try again.']);
			}
		} else {
			return response()->json(['status'=>'error', 'message'=>'This membership package cannot be deleted as transactions have been completed using this membership package.']);
		}
	}

	/**
	 * Get membership package list data.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getMembershipPackageData(Request $request, $club)
	{
		$clubId = getClubIdBySlug($club);
		$membershipPackage = $this->membershipPackageService->getData(
			$clubId,
			$request->all()
		);

		return $membershipPackage;
	}
}
