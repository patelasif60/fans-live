<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CTA\StoreRequest;
use App\Http\Requests\CTA\UpdateRequest;
use App\Models\CTA;
use App\Services\CTAService;
use Illuminate\Http\Request;
use JavaScript;

class CTAController extends Controller
{
    /**
     * A CTA service.
     *
     * @var ctaService
     */
    protected $ctaService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CTAService $ctaService)
    {
        $this->middleware('auth');
        $this->ctaService = $ctaService;
    }

    /**
     * Destory/Unset object variables.
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->ctaService);
    }

    /**
     * Display a listing of CTAs.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.ctas.index');
    }

    /**
     * Show the form for creating a new CTA.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $status = config('fanslive.PUBLISH_STATUS');
        $buttonActions = config('fanslive.CTA_BUTTON_ACTIONS');
        $buttonItems = config('fanslive.CTA_BUTTON_ITEMS');

        return view('backend.ctas.create', compact('status', 'buttonActions', 'buttonItems'));
    }

    /**
     * Store a newly created CTA.
     *
     * @param $club
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, $club)
    {
        $clubId = getClubIdBySlug($club);
        $cta = $this->ctaService->create(
            $clubId,
            auth()->user(),
            $request->all()
        );
        if ($cta) {
            flash('CTA created successfully')->success();
        } else {
            flash('CTA could not be created. Please try again.')->error();
        }

        return redirect()->route('backend.cta.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $request
     * @param int $clubId
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $clubId, CTA $cta)
    {
        $status = config('fanslive.PUBLISH_STATUS');
        $buttonActions = config('fanslive.CTA_BUTTON_ACTIONS');
        $buttonItems = config('fanslive.CTA_BUTTON_ITEMS');
        JavaScript::put([
            'buttonOneActionItem' => $cta->button1_item,
            'buttonTwoActionItem' => $cta->button2_item,
        ]);

        return view('backend.ctas.edit', compact('status', 'buttonActions', 'buttonItems', 'cta'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $clubId, CTA $cta)
    {
        $ctaToUpdate = $this->ctaService->update(
            auth()->user(),
            $cta,
            $request->all()
        );

        if ($ctaToUpdate) {
            flash('CTA updated successfully')->success();
        } else {
            flash('CTA could not be updated. Please try again.')->error();
        }

        return redirect()->route('backend.cta.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($clubId, CTA $cta)
    {
        $ctaImageToDelete = $this->ctaService->deleteLogo(
            $cta
        );

        if ($ctaImageToDelete && $cta->delete()) {
            flash('CTA deleted successfully')->success();
        } elseif (!$ctaImageToDelete && $cta->delete()) {
            flash('CTA deleted successfully')->success();
        } else {
            flash('CTA could not be deleted. Please try again.')->error();
        }

        return redirect()->route('backend.cta.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Get cta list data.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCTAData(Request $request, $club)
    {
        $clubId = getClubIdBySlug($club);
        $ctaList = $this->ctaService->getData(
            $clubId,
            $request->all()
        );

        return $ctaList;
    }
}
