<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StadiumEntrance\UpdateRequest;
use App\Models\StadiumBlock;
use App\Models\StadiumEntrance;
use App\Models\StadiumGeneralSetting;
use App\Services\StadiumEntranceService;
use Illuminate\Http\Request;
use JavaScript;

/**
 * StadiumEntrance Controller class to handle request.
 */
class StadiumEntranceController extends Controller
{
    /**
     * The StadiumEntrance service instance.
     *
     * @var service
     */
    public function __construct(StadiumEntranceService $service)
    {
        $this->service = $service;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $club, StadiumEntrance $stadiumEntrance)
    {
        $clubId = getClubIdBySlug($club);

        $stadiumGeneralSetting = StadiumGeneralSetting::where('club_id', $clubId)->first();

        //## Get stadium Block
        $stadiumBlocks = StadiumBlock::where('club_id', $clubId)->get()->pluck('name', 'id');

        $stadiumEntrance = StadiumEntrance::with(['stadiumEntranceBlocks'])->where('club_id', $clubId)->get();

        $stadiumEntrance = $this->service->prepareBlocksData($stadiumEntrance, $stadiumBlocks);

        $markers = JavaScript::put([
            'markers' => config('fanslive.MARKERS_IMAGE'),
        ]);

        return view('backend.stadiumentrances.edit', compact('stadiumEntrance', 'stadiumGeneralSetting', 'stadiumBlocks'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $club)
    {
		$clubId = getClubIdBySlug($club);
	    $stadiumEntranceUpdate = $this->service->update(
            auth()->user(),
            $clubId,
            $request->all()
        );

        if($request->status_flag != Null){
			if ($stadiumEntranceUpdate) {
				flash('Stadium entrance updated successfully')->success();
			} else {
				flash('Stadium entrance setting could not be updated. Please try again.')->error();
			}
		}else{
			flash('Stadium entrance deleted successfully')->success();
		}


        return redirect()->route('backend.stadiumentrance.edit', ['club' => app()->request->route('club')]);
    }

    /**
     * Get General settings.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function getGenralSettingData(Request $request)
    {
        $stadiumEntranceUpdate = $this->service->updateGenralSettingData($request);

        return $stadiumEntranceUpdate;
    }

    /**
     * unset class instance.
     */
    public function __destruct()
    {
        unset($this->service);
    }
}
