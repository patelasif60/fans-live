<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StadiumGeneralSetting\UpdateRequest;
use App\Models\StadiumGeneralSetting;
use App\Services\StadiumGeneralSettingService;
use Illuminate\Http\Request;

class StadiumGeneralSettingController extends Controller
{
    /**
     * A StadiumGeneralSetting service.
     *
     * @var stadiumGeneralSettingService
     */
    protected $stadiumGeneralSettingService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(StadiumGeneralSettingService $stadiumGeneralSettingService)
    {
        $this->middleware('auth');
        $this->stadiumGeneralSettingService = $stadiumGeneralSettingService;
    }

    /**
     * Destory/Unset object variables.
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->stadiumGeneralSettingService);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $request
     * @param int $clubId
     * @param int $stadiumGeneralSetting
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $club, StadiumGeneralSetting $stadiumGeneralSetting)
    {
        $clubId = getClubIdBySlug($club);
        $stadiumGeneralSetting = StadiumGeneralSetting::where('club_id', $clubId)->first();

        return view('backend.stadiumgeneralsettings.edit', compact('stadiumGeneralSetting'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  $club
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $club)
    {
        $clubId = getClubIdBySlug($club);
        $stadiumGeneralSettingUpdate = $this->stadiumGeneralSettingService->update(
            auth()->user(),
            $clubId,
            $request->all()
        );

        if ($stadiumGeneralSettingUpdate) {
            flash('Stadium general setting updated successfully')->success();
        } else {
            flash('Stadium general setting could not be updated. Please try again.')->error();
        }

        return redirect()->route('backend.stadiumgeneralsettings.edit', ['club' => app()->request->route('club')]);
    }
}
