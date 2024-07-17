<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\HospitalitySuite\UpdateRequest;
use App\Models\Club;
use App\Models\HospitalitySuite;
use App\Models\HospitalitySuiteTransaction;
use App\Services\HospitalitySuiteService;
use Illuminate\Http\Request;

class HospitalitySuiteController extends Controller
{
    public function __construct(HospitalitySuiteService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
	 * @param $club
	 * @return \Illuminate\Http\Response
	 */
	public function index($club)
	{
		$club = Club::where('slug',$club)->first();
		$currencySymbol = config('fanslive.CURRENCY_SYMBOL');
		$currencyIcon =  $currencySymbol[$club->currency];

        return view('backend.hospitalitysuites.index', compact('currencyIcon'));
    }

    /**
     * Show the form for creating a new resource.
     *
	 * @param $club
	 * @return \Illuminate\Http\Response
	 */
	public function create($club)
	{
		$club = Club::where('slug',$club)->first();
		$currencySymbol = config('fanslive.CURRENCY_SYMBOL');
        return view('backend.hospitalitysuites.create', compact('club', 'currencySymbol'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $club)
    {
        $clubId = getClubIdBySlug($club);
        $hospitalitySuites = $this->service->create(
            $clubId,
            auth()->user(),
            $request->all()
        );

        if ($hospitalitySuites) {
            flash('Hospitality suite created successfully')->success();
        } else {
            flash('Hospitality suite could not be created. Please try again.')->error();
        }

        return redirect()->route('backend.hospitalitysuite.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $clubId, HospitalitySuite $hospitalitySuites)
    {
		$hospitalityDietaryOptions=$hospitalitySuites->hospitalityDietaryOptions->pluck('option_name','id')->toArray();
        $club = Club::where('slug',$clubId)->first();
		$currencySymbol = config('fanslive.CURRENCY_SYMBOL');
        return view('backend.hospitalitysuites.edit', compact('hospitalitySuites', 'club' ,'currencySymbol','hospitalityDietaryOptions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $clubId, HospitalitySuite $hospitalitySuites)
    {
        $hospitalitySuitesToUpdate = $this->service->update(
            auth()->user(),
            $hospitalitySuites,
            $request->all()
        );

        if ($hospitalitySuitesToUpdate) {
            flash('Hospitality suite updated successfully')->success();
        } else {
            flash('Hospitality suite could not be updated. Please try again.')->error();
        }

        return redirect()->route('backend.hospitalitysuite.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($clubId, HospitalitySuite $hospitalitySuites)
    {
        $hospitalitySuiteTransactions = HospitalitySuiteTransaction::where('hospitality_suite_id',$hospitalitySuites->id)->where('status','successful')->get();
        if (count($hospitalitySuiteTransactions) == 0) {
            $this->service->deleteLogo($hospitalitySuites);
            if ($hospitalitySuites->delete()) {
                return response()->json(['status'=>'success', 'message'=>'Hospitality suite deleted successfully']);
            } else {
                return response()->json(['status'=>'error', 'message'=>'Hospitality suite could not be deleted. Please try again.']);
            }
        } else {
            return response()->json(['status'=>'error', 'message'=>'This hospitality suite cannot be deleted as transactions have been completed using this hospitality suite.']);
        }
    }

    /**
     * Get Hospitality suites list data.
     *
     * @return \Illuminate\Http\Response
     */
    public function getHospitalitySuitesData(Request $request, $club)
    {
        $clubId = getClubIdBySlug($club);
        $hospitalitySuitesList = $this->service->getData(
            $request->all(),
            $clubId
        );

        return $hospitalitySuitesList;
    }
}
