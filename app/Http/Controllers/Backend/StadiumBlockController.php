<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StadiumBlock\StoreRequest;
use App\Http\Requests\StadiumBlock\UpdateRequest;
use App\Models\StadiumBlock;
use App\Models\StadiumGeneralSetting;
use App\Services\StadiumBlockService;
use Illuminate\Http\Request;

class StadiumBlockController extends Controller
{
    /**
     * A Stadium Block service.
     *
     * @var contentFeed
     */
    protected $stadiumBlockService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(StadiumBlockService $stadiumBlockService)
    {
        $this->middleware('auth');
        $this->stadiumBlockService = $stadiumBlockService;
    }

    /**
     * Destory/Unset object variables.
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->stadiumBlockService);
    }

    /**
     * Display a listing of stadium block.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.stadiumblocks.index');
    }

    /**
     * Show the form for creating a new stadium block.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($club)
    {
        $clubId = getClubIdBySlug($club);
        $stadiumGeneralSetting = StadiumGeneralSetting::where('club_id', $clubId)->first();
        // $otherStadiumBlock = StadiumBlock::where('club_id', $clubId)->get();
        $areas = $this->stadiumBlockService->getArea($clubId);

        return view('backend.stadiumblocks.create', compact('stadiumGeneralSetting', 'areas'));
    }

    /**
     * Store a newly created stadium block.
     *
     * @param $club
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, $club)
    {
        $clubId = getClubIdBySlug($club);
        $stadiumBlocks = $this->stadiumBlockService->create(
            $clubId,
            auth()->user(),
            $request->all()
        );
        if ($stadiumBlocks) {
            flash('Stadium block created successfully')->success();
        } else {
            flash('Sheet is invalid. Please try again.')->error();
        }

        return redirect()->route('backend.stadiumblocks.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $request
     * @param int $clubId
     * @param int $stadiumBlock
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $clubId, StadiumBlock $stadiumBlock)
    {
        $clubId = getClubIdBySlug($clubId);
        $stadiumGeneralSetting = StadiumGeneralSetting::where('club_id', $clubId)->first();
        // $otherStadiumBlock = StadiumBlock::where('club_id', $clubId)->get();
        $areas = $this->stadiumBlockService->getArea($clubId);

        return view('backend.stadiumblocks.edit', compact('stadiumBlock', 'stadiumGeneralSetting', 'areas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $request
     * @param  $clubId
     * @param  $stadiumBlock
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $clubId, StadiumBlock $stadiumBlock)
    {
        $stadiumBlockToUpdate = $this->stadiumBlockService->update(
            auth()->user(),
            $stadiumBlock,
            $request->all()
        );

        if ($stadiumBlockToUpdate) {
            flash('Stadium block updated successfully')->success();
        } else {
            flash('Sheet is invalid. Please try again.')->error();
        }

        return redirect()->route('backend.stadiumblocks.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Remove the specified stadium block.
     *
     * @param $clubId
     * @param $stadiumBlock
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($clubId, StadiumBlock $stadiumBlock)
    {
        $stadiumBlockToDelete = $this->stadiumBlockService->deleteFile(
            $stadiumBlock
        );

        if ($stadiumBlockToDelete && $stadiumBlock->delete()) {
            flash('Stadium block deleted successfully')->success();
        } elseif (!$stadiumBlockToDelete && $stadiumBlock->delete()) {
            flash('Stadium block deleted successfully')->success();
        } else {
            flash('Stadium block could not be deleted. Please try again.')->error();
        }

        return redirect()->route('backend.stadiumblocks.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Get stadium block list data.
     *
     * @return \Illuminate\Http\Response
     */
    public function getStadiumBlockdata(Request $request, $club)
    {
        $clubId = getClubIdBySlug($club);
        $stadiumBlock = $this->stadiumBlockService->getData(
            $clubId,
            $request->all()
        );

        return $stadiumBlock;
    }
}
