<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Match\UpdateRequest;
use App\Models\Club;
use App\Models\Competition;
use App\Models\Match;
use App\Models\MatchPlayer;
use App\Models\MembershipPackage;
use App\Models\Player;
use App\Models\PricingBand;
use App\Models\StadiumBlock;
use App\Models\HospitalitySuite;
use App\Services\MatchService;
use DB;
use Illuminate\Http\Request;
use JavaScript;
use App\Models\BookedTicket;

class MatchController extends Controller
{
    /**
     * A Match service.
     *
     * @var matchService
     */
    protected $matchService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(MatchService $matchService)
    {
        $this->middleware('auth');
        $this->matchService = $matchService;
    }

    /**
     * Destory/Unset object variables.
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->matchService);
    }

    /**
     * Display a listing of membership package.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($clubId)
    {

        $club = getClubIdBySlug($clubId);
        $competitions = Club::find($club);
        $dbOppositions = DB::table('matches')
                       ->join('clubs', 'clubs.id', '=', 'matches.home_team_id')
                       ->join('clubs as club_away', 'club_away.id', '=', 'matches.away_team_id')
                       ->where('matches.home_team_id', $club)
                       ->orWhere('matches.away_team_id', $club)
                       ->select('clubs.name as home_team_name', 'club_away.name as away_team_name', 'clubs.id as home_team_id', 'club_away.id as away_team_id')
                       ->get();
        $oppositions = $dbOppositions->pluck('away_team_name', 'away_team_id')->union($dbOppositions->pluck('home_team_name', 'home_team_id'))->unique()->except($club);
		JavaScript::put([
			'dateTimeCmsFormat' => config('fanslive.DATE_TIME_CMS_FORMAT.js'),
		]);
        return view('backend.matches.index', compact('club', 'competitions', 'oppositions'));
    }

    /**
     * Show the form for creating a new match.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($clubId)
    {
        $clubs = Club::all()->sortBy('name');
        $matchEventtype = config('fanslive.MATCH_EVENT_TYPE');
        $ticketResaleFeeType = config('fanslive.TICKET_RESALE_FEE_TYPE');
        $club = getClubIdBySlug($clubId);
        $availableBlocks = StadiumBlock::where('club_id', $club)->get();
        $pricingBands = PricingBand::where('club_id', $club)->get();
        $membershipPackage = MembershipPackage::where('club_id', $club)->orWhere('club_id', null)->get();
        $hospitalitySuites = HospitalitySuite::where('club_id', $club)->get();
        $players = Player::all();
        $all_fans = config('fanslive.ALL_FANS_MEMBERSHIP_PACKAGE_ID');
        JavaScript::put([
            'matchEventtype' => $matchEventtype,
            'clubId'         => $club,
            'players'        => $players,
            'all_fans'       => $all_fans,
        ]);

        return view('backend.matches.create', compact('clubs', 'ticketResaleFeeType', 'availableBlocks', 'pricingBands', 'membershipPackage', 'hospitalitySuites','all_fans'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $club
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $match = $this->matchService->create(
            $request->all()
        );
        if ($match) {
            flash('Match created successfully')->success();
        } else {
            flash('Match could not be created. Please try again.')->error();
        }

        return redirect()->route('backend.matches.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  $clubId
     * @param  $match
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $clubId, Match $match)
    {
        $clubs = Club::all()->sortBy('name');
        $homeLineupPlayer = MatchPlayer::with('player')->where([['type', '=', 'lineup'], ['match_id', '=', $match->id], ['club_id', '=', $match->home_team_id]])->get();
        $homeBenchPlayer = MatchPlayer::with('player')->where([['type', '=', 'bench'], ['match_id', '=', $match->id], ['club_id', '=', $match->home_team_id]])->get();
        $awayLineupPlayer = MatchPlayer::with('player')->where([['type', '=', 'lineup'], ['match_id', '=', $match->id], ['club_id', '=', $match->away_team_id]])->get();
        $awayBenchPlayer = MatchPlayer::with('player')->where([['type', '=', 'bench'], ['match_id', '=', $match->id], ['club_id', '=', $match->away_team_id]])->get();
        $matchEventtype = config('fanslive.MATCH_EVENT_TYPE');
        $ticketResaleFeeType = config('fanslive.TICKET_RESALE_FEE_TYPE');

        $currentClub = getClubIdBySlug($clubId);
        $availableBlocks = StadiumBlock::where('club_id', $currentClub)->get();
        $pricingBands = PricingBand::where('club_id', $currentClub)->get();
        $membershipPackage = MembershipPackage::where('club_id', $currentClub)->orWhere('club_id', null)->get();
        $players = Player::all();
        $hospitalitySuites = HospitalitySuite::where('club_id', $currentClub)->get();
        $all_fans = config('fanslive.ALL_FANS_MEMBERSHIP_PACKAGE_ID');
        $clubArray = $match->player->pluck('club_id')->toArray();
        $homeFlag=$awayFlag=0;
        if(in_array($match->home_team_id, $clubArray))
        {
            $homeFlag = 1;
        }
        if(in_array($match->away_team_id, $clubArray))
        {
            $awayFlag = 1;
        }
        $transactionIds = $match->ticketTransactions->pluck('id');
        $bookedSeats = BookedTicket::whereIn('ticket_transaction_id',$transactionIds)->count();
        JavaScript::put([
            'matchEventtype'   => $matchEventtype,
            'homeLineupPlayer' => $homeLineupPlayer,
            'homeBenchPlayer'  => $homeBenchPlayer,
            'awayLineupPlayer' => $awayLineupPlayer,
            'awayBenchPlayer'  => $awayBenchPlayer,
            'clubId'           => $currentClub,
            'homeTeam'         => $match->homeTeam,
            'awayTeam'         => $match->awayTeam,
            'home'             => $match->home_team_id,
            'away'             => $match->away_team_id,
            'players'          => $players,
            'all_fans'       => $all_fans,
        ]);

        return view('backend.matches.edit', compact('clubs', 'match', 'matchEventtype', 'homeLineupPlayer', 'homeBenchPlayer', 'awayLineupPlayer', 'awayBenchPlayer', 'ticketResaleFeeType', 'availableBlocks', 'pricingBands', 'currentClub', 'membershipPackage', 'players', 'hospitalitySuites','homeFlag','awayFlag','all_fans','bookedSeats'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  $clubId
     * @param  $match
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $clubId, Match $match)
    {
        $club = getClubIdBySlug($clubId);
        $matchToUpdate = $this->matchService->update(
            $match,
            $request->all(),
            $club
        );

        if ($matchToUpdate) {
            flash('Match updated successfully')->success();
        } else {
            flash('Match could not be updated. Please try again.')->error();
        }

        return redirect()->route('backend.matches.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $clubId
     *  @param $match
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($clubId, Match $match)
    {
        $imagesToDelete = $this->matchService->deleteImage($match->id);

        if ($match->delete()) {
            flash('Match deleted successfully')->success();
        } else {
            flash('Match could not be deleted. Please try again.')->error();
        }

        return redirect()->route('backend.matches.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Get Match list data.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMatchData(Request $request, $club)
    {
        $clubId = getClubIdBySlug($club);
        $matchist = $this->matchService->getData(
            $request->all(),
            $clubId
        );

        return $matchist;
    }

    /**
     * Add player.
     *
     * @return \Illuminate\Http\Response
     */
    public function addPlayer(Request $request)
    {
        return $this->matchService->addPlayer($request->all());
    }
}
