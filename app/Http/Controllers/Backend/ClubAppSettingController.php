<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ClubAppSettingService;
use App\Services\ClubService;
use App\Http\Requests\ClubAppSetting\UpdateRequest;

class ClubAppSettingController extends Controller
{
    /**
     * A ClubAppSettingService service.
     *
     * @var ClubAppSettingService
     */
    protected $clubAppSettingService;

    /**
     * A ClubService service.
     *
     * @var ClubService
     */
    protected $clubService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ClubAppSettingService $clubAppSettingService, ClubService $clubService)
    {
        $this->middleware('auth');
        $this->clubAppSettingService = $clubAppSettingService;
        $this->clubService = $clubService;
    }

	/**
    * Display a listing of Club information page.
    *
    * @return \Illuminate\Http\Response
    */
    public function edit(Request $request, $club)
    {
    	$clubId = getClubIdBySlug($club);
    	$club = $this->clubService->getClubDetail($clubId);
        $modules = $this->clubAppSettingService->getAllModules();
        $activeModules = $club->moduleSettings->where('is_active', 1)->pluck('module_id')->toArray();

        return view('backend.clubappsettings.edit', compact("club", "modules", "activeModules"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $club
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $club)
    {
    	$clubId = getClubIdBySlug($club);
        $modules = $this->clubAppSettingService->getAllModules()->pluck('id')->toArray();
        $clubAppSettingUpdate = $this->clubAppSettingService->update(
            auth()->user(),
            $clubId,
            $request->all(),
            $modules
        );

        if ($clubAppSettingUpdate) {
            flash('Club app setting updated successfully')->success();
        } else {
            flash('Club app setting could not be updated. Please try again.')->error();
        }

        return redirect()->back();
    }

    
}
