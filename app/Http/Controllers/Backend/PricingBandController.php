<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\PricingBand\StoreRequest;
use App\Http\Requests\PricingBand\UpdateRequest;
use App\Models\PricingBand;
use App\Services\PricingBandService;
use Illuminate\Http\Request;
use App\Models\Club;

class PricingBandController extends Controller
{
    /**
     * A Pricing Band service.
     *
     * @var pricingBandService
     */
    protected $pricingBandService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PricingBandService $pricingBandService)
    {
        $this->middleware('auth');
        $this->pricingBandService = $pricingBandService;
    }

    /**
     * Destory/Unset object variables.
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->pricingBandService);
    }

    /**
     * Display a listing of pricing band.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($club)
    {
        $club = Club::where('slug',$club)->first();
        $currencySymbol = config('fanslive.CURRENCY_SYMBOL');
        $currencyIcon =  $currencySymbol[$club->currency];
        return view('backend.pricingbands.index',compact('currencyIcon'));
    }

    /**
     * Show the form for creating a new pricing band.
     * @param $club
     * @return \Illuminate\Http\Response
     */
    public function create($club)
    {
		$club = Club::where('slug',$club)->first();
		$currencySymbol = config('fanslive.CURRENCY_SYMBOL');
        return view('backend.pricingbands.create', compact('club', 'currencySymbol'));
    }

    /**
     * Store a newly created pricing band.
     *
     * @param $club
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, $club)
    {
        $clubId = getClubIdBySlug($club);
        $pricingBands = $this->pricingBandService->create(
            $clubId,
            auth()->user(),
            $request->all()
        );
        if ($pricingBands) {
            flash('Pricing band created successfully')->success();
        } else {
            flash('Sheet is invalid. Please try again.')->error();
        }

        return redirect()->route('backend.pricingbands.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $request
     * @param int $clubId
     * @param int $pricingBand
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $clubId, PricingBand $pricingBand)
    {
		$club = Club::where('slug',$clubId)->first();
		$currencySymbol = config('fanslive.CURRENCY_SYMBOL');
        return view('backend.pricingbands.edit', compact('pricingBand', 'club' ,'currencySymbol'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $clubId
     * @param int                      $pricingBand
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $clubId, PricingBand $pricingBand)
    {
        $pricingBandToUpdate = $this->pricingBandService->update(
            auth()->user(),
            $pricingBand,
            $request->all()
        );

        if ($pricingBandToUpdate) {
            flash('Pricing band updated successfully')->success();
        } else {
            flash('Sheet is invalid. Please try again.')->error();
        }

        return redirect()->route('backend.pricingbands.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Remove the specified pricing band.
     *
     * @param int $pricingBand
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($clubId, PricingBand $pricingBand)
    {
        $pricingBandToDelete = $this->pricingBandService->deleteFile(
            $pricingBand
        );

        if ($pricingBandToDelete && $pricingBand->delete()) {
            flash('Pricing band deleted successfully')->success();
        } elseif (!$pricingBandToDelete && $pricingBand->delete()) {
            flash('Pricing band deleted successfully')->success();
        } else {
            flash('Pricing band could not be deleted. Please try again.')->error();
        }

        return redirect()->route('backend.pricingbands.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Get pricing band list data.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPricingBandData(Request $request, $club)
    {
    	$clubId = getClubIdBySlug($club);
        $pricingBand = $this->pricingBandService->getData(
        	$clubId,
            $request->all()
        );

        return $pricingBand;
    }

    /**
     * Validate seat data.
     *
     * @return \Illuminate\Http\Response
     */
    public function validateSeatData(Request $request)
    {
        $validateSeatData = $this->pricingBandService->validateSeatData(
            $request->all()
        );

        return $validateSeatData;
    }
}
