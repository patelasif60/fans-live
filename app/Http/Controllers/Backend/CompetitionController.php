<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Competition\StoreRequest;
use App\Http\Requests\Competition\UpdateRequest;
use App\Models\Club;
use App\Models\Competition;
use App\Services\CompetitionService;
use Illuminate\Http\Request;
use JavaScript;

class CompetitionController extends Controller
{
    /**
     * A Competition service.
     *
     * @var competitionService
     */
    protected $competitionService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CompetitionService $competitionService)
    {
        $this->middleware('auth');
        $this->competitionService = $competitionService;
    }

    /**
     * Display a listing of competition.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.competitions.index');
    }

    /**
     * Show the form for creating a new competition.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clubs = Club::all();
        $competitionStatus = config('fanslive.PUBLISH_STATUS');
        JavaScript::put([
            'clubs' => $clubs,
        ]);

        return view('backend.competitions.create', compact('competitionStatus', 'clubs'));
    }

    /**
     * Store a newly created competition.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $competition = $this->competitionService->create(
            auth()->user(),
            $request->all()
        );
        if ($competition) {
            flash('Competition created successfully')->success();
        } else {
            flash('Competition could not be created. Please try again.')->error();
        }

        return redirect()->route('backend.competition.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  $competition
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Competition $competition)
    {
        $clubs = Club::all();
        $competitionStatus = config('fanslive.PUBLISH_STATUS');
        JavaScript::put([
            'clubs'            => $clubs,
            'competitionClubs' => $competition->clubs()->get(),
        ]);

        return view('backend.competitions.edit', compact('competitionStatus', 'competition'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  $competition
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Competition $competition)
    {
        $competitionToUpdate = $this->competitionService->update(
            auth()->user(),
            $competition,
            $request->all()
        );

        if ($competitionToUpdate) {
            flash('Competition updated successfully')->success();
        } else {
            flash('Competition could not be updated. Please try again.')->error();
        }

        return redirect()->route('backend.competition.index');
    }

    /**
     * Remove the specified category.
     *
     * @param  $competition
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Competition $competition)
    {
        $competitionLogoDelete = $this->competitionService->deleteLogo(
            $competition
        );

        if ($competitionLogoDelete && $competition->delete()) {
            flash('Competition deleted successfully')->success();
        } elseif (!$competitionLogoDelete && $competition->delete()) {
            flash('Competition deleted successfully')->success();
        } else {
            flash('Competition could not be deleted. Please try again.')->error();
        }

        return redirect()->route('backend.competition.index');
    }

    /**
     * Get Competition list data.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCompetitionData(Request $request)
    {
        $competitionList = $this->competitionService->getData(
            $request->all()
        );

        return $competitionList;
    }
}
