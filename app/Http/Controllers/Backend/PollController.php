<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Poll\StoreRequest;
use App\Http\Requests\Poll\UpdateRequest;
use App\Models\Match;
use App\Models\Poll;
use App\Models\PollOption;
use App\Services\PollService;
use App\Services\MatchService;
use Illuminate\Http\Request;
use JavaScript;
use Carbon\Carbon;

class PollController extends Controller
{
    /**
     * A Poll service.
     *
     * @var PollService
     */
    protected $pollService;

    /**
     * A match service.
     *
     * @var MatchService
     */
    protected $matchService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PollService $pollService, MatchService $matchService)
    {
        $this->middleware('auth');
        $this->pollService = $pollService;
        $this->matchService = $matchService;
    }

    /**
     * Display a listing of poll.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($clubId)
    {
        $club = getClubIdBySlug($clubId);
        return view('backend.polls.index', compact('club'));
    }

    /**
     * Show the form for creating a new poll.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($club)
    {
        $pollStatus = config('fanslive.PUBLISH_STATUS');
        $clubId = getClubIdBySlug($club);
        $pollAssociatedMatch = $this->matchService->getCurrentAndFutureMatches($clubId);
        return view('backend.polls.create', compact('pollStatus', 'pollAssociatedMatch'));
    }

    /**
     * Store a newly created poll.
     *
     * @param $club
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, $club)
    {
        $clubId = getClubIdBySlug($club);
        $poll = $this->pollService->create(
            $clubId,
            auth()->user(),
            $request->all()
        );
        if ($poll) {
            flash('Poll created successfully')->success();
        } else {
            flash('Poll could not be created. Please try again.')->error();
        }

        return redirect()->route('backend.poll.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $clubId
     * @param $poll
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $clubId, Poll $poll)
    {
        $pollStatus = config('fanslive.PUBLISH_STATUS');
        $club = getClubIdBySlug($clubId);
        $pollAssociatedMatch = $this->matchService->getCurrentAndFutureMatches($club, $poll->associated_match);
        $pollOption = $this->pollService->getPollOptions($poll->id);
        JavaScript::put([
            'pollAnswer' => $poll->answers,
        ]);

        return view('backend.polls.edit', compact('pollStatus', 'poll', 'pollAssociatedMatch', 'pollOption'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $clubId
     * @param $poll
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $clubId, Poll $poll)
    {
        $pollUpdate = $this->pollService->update(
            auth()->user(),
            $poll,
            $request->all()
        );

        if ($pollUpdate) {
            flash('Poll updated successfully')->success();
        } else {
            flash('Poll could not be updated. Please try again.')->error();
        }

        return redirect()->route('backend.poll.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Remove the specified poll.
     *
     * @param  $clubId
     * @param  $poll
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($clubId, Poll $poll)
    {
        if ($poll->delete()) {
            flash('Poll deleted successfully')->success();
        } else {
            flash('Poll could not be deleted. Please try again.')->error();
        }

        return redirect()->route('backend.poll.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Get Poll list data.
     *
     * @param  $club
     *
     * @return \Illuminate\Http\Response
     */
    public function getPollData(Request $request, $club)
    {
        $clubId = getClubIdBySlug($club);
        $pollList = $this->pollService->getData(
            $clubId,
            $request->all()
        );

        return $pollList;
    }
}
