<?php

namespace App\Repositories;

use App\Models\Club;
use App\Models\Match;
use App\Models\TicketTransaction;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Arr;

/**
 * Repository class for model.
 */
class MatchRepository extends BaseRepository
{
	/**
	 * The match event repository instance.
	 *
	 * @var repository
	 */
	protected $matchEventRepository;

	/**
	 * Create a new match repository instance.
	 *
	 * @return void
	 */
	public function __construct(MatchEventRepository $matchEventRepository, MatchPlayerRepository $matchPlayerRepository)
	{
		$this->matchEventRepository = $matchEventRepository;
		$this->matchPlayerRepository = $matchPlayerRepository;
	}

	/**
	 * Destroy an instance.
	 *
	 * @return void
	 */
	public function __destruct()
	{
		unset($this->matchEventRepository);
		unset($this->matchPlayerRepository);
	}

	/**
	 * Handle logic to create a new match.
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function create($data)
	{
		$matchData = [
			'api_id' => isset($data['api_id']) ? $data['api_id'] : null,
			'competition_id' => $data['competition_id'],
			'status' => $data['status'],
			'minute' => isset($data['minute']) ? $data['minute'] : null,
			'attendance' => isset($data['attendance']) ? $data['attendance'] : null,
			'stage' => isset($data['stage']) ? $data['stage'] : null,
			'matchday' => isset($data['matchday']) ? $data['matchday'] : null,
			'group' => isset($data['group']) ? $data['group'] : null,
			'last_updated' => $data['last_updated'],
			'kickoff_time' => $data['kickoff_time'],
			'is_match_imported' => $data['is_match_imported'],
			'duration' => isset($data['duration']) ? $data['duration'] : null,
			'full_time_home_team_score' => $data['full_time_home_team_score'],
			'full_time_away_team_score' => $data['full_time_away_team_score'],
			'half_time_home_team_score' => isset($data['half_time_home_team_score']) ? $data['half_time_home_team_score'] : null,
			'half_time_away_team_score' => isset($data['half_time_away_team_score']) ? $data['half_time_away_team_score'] : null,
			'extra_time_home_team_score' => $data['extra_time_home_team_score'],
			'extra_time_away_team_score' => $data['extra_time_away_team_score'],
			'penalties_home_team_score' => $data['penalties_home_team_score'],
			'penalties_away_team_score' => $data['penalties_away_team_score'],
			'home_team_id' => $data['home_team_id'],
			'away_team_id' => $data['away_team_id'],
			'referees' => isset($data['referees']) ? $data['referees'] : null,
			'is_published' => isset($data['is_published']) ? $data['is_published'] : null,
			'winner' => isset($data['winner']) ? $data['winner'] : null,
			'is_ticket_sale_enabled' => Arr::get($data, 'is_ticket_sale_enabled', 0),
			'is_hospitality_ticket_sale_enabled' => Arr::get($data, 'is_hospitality_ticket_sale_enabled', 0),
		];
		$match = Match::create($matchData);

		return $match;
	}

	/**
	 * Check if match Id exists.
	 *
	 * @param $matchId
	 *
	 * @return mixed
	 */
	public function getMatchById($matchId)
	{
		$match = Match::where('api_id', $matchId)->first();
		return $match;
	}

	/**
	 * Get all matches.
	 *
	 * @return mixed
	 */
	public function getAllUnfinishedMatches()
	{
		$matches = Match::whereNotNull('api_id')->where('status', '!=', 'finished')->get();
		return $matches;
	}

	/**
	 * Get all matches.
	 *
	 * @return mixed
	 */
	public function getCompetitionMatches($competitionId)
	{
		$matches = Match::whereNotNull('api_id')->where('competition_id', $competitionId)->where('status', '!=', 'finished')->get();
		return $matches;
	}

	/**
	 * Get matches within three hour interval.
	 *
	 * @return mixed
	 */
	public function getMatchesWithinRange()
	{
		$currentTime = Carbon::now()->format('Y-m-d H:i:s');
		$beforeOneHourTime = Carbon::now()->subHour(1)->format('Y-m-d H:i:s');
		$afterTwoHourTime = Carbon::now()->addHour(2)->format('Y-m-d H:i:s');
		$matches = Match::where('kickoff_time', '>=', $beforeOneHourTime)
							->where('kickoff_time', '<=', $afterTwoHourTime)
							->whereNotNull('api_id')
							->get();

		return $matches;
	}

	/**
	 * Update match detail.
	 *
	 * @param $matchId
	 * @param $data
	 *
	 * @return mixed
	 */
	public function updateMatchDetail(&$match, $data)
	{
		if(isset($data['status'])) {
			$match->status = $data['status'];
		}
		if (isset($data['attendance'])) {
			$match->attendance = $data['attendance'];
		}
		if (isset($data['referees'])) {
			$match->referees = $data['referees'];
		}
		if (isset($data['winner'])) {
			$match->winner = $data['winner'];
		}
		if (isset($data['duration'])) {
			$match->duration = $data['duration'];
		}
		if (isset($data['home_team_id'])) {
			$match->home_team_id = $data['home_team_id'];
		}
		if (isset($data['away_team_id'])) {
			$match->away_team_id = $data['away_team_id'];
		}
		if (isset($data['kickoff_time'])) {
			$match->kickoff_time = $data['kickoff_time'];
		}
		if (isset($data['is_published'])) {
			$match->is_published = $data['is_published'];
		}
		$match->full_time_home_team_score = $data['full_time_home_team_score'];
		$match->full_time_away_team_score = $data['full_time_away_team_score'];
		if (isset($data['half_time_home_team_score'])) {
			$match->half_time_home_team_score = $data['half_time_home_team_score'];
		}
		if (isset($data['half_time_away_team_score'])) {
			$match->half_time_away_team_score = $data['half_time_away_team_score'];
		}
		$match->extra_time_home_team_score = $data['extra_time_home_team_score'];
		$match->extra_time_away_team_score = $data['extra_time_away_team_score'];
		$match->penalties_home_team_score = $data['penalties_home_team_score'];
		$match->penalties_away_team_score = $data['penalties_away_team_score'];
		if (isset($data['is_ticket_sale_enabled'])) {
			$match->is_ticket_sale_enabled = $data['is_ticket_sale_enabled'];
		}
		if (isset($data['is_hospitality_ticket_sale_enabled'])) {
			$match->is_hospitality_ticket_sale_enabled = $data['is_hospitality_ticket_sale_enabled'];
		}

		$match->save();

		return $match;
	}

	/**
	 * Update match detail.
	 *
	 * @param $user
	 * @param $match
	 * @param $data
	 *
	 * @return mixed
	 */
	public function update($user, $match, $data)
	{
	}

	/**
	 * Get Competitoin data.
	 *
	 * @param $data
	 * @param $clubId
	 *
	 * @return mixed
	 */
	public function getData($data, $clubId)
	{
		$matchData = DB::table('matches')
			->join('competitions', 'competitions.id', '=', 'matches.competition_id')
			->join('clubs', 'clubs.id', '=', 'matches.home_team_id')
			->join('clubs as club_away', 'club_away.id', '=', 'matches.away_team_id')
			->select('matches.*', 'competitions.name as competition_name', 'clubs.name as home_team_name', 'club_away.name as away_team_name', 'clubs.id as home_team_id', 'club_away.id as away_team_id')
			->where(function ($query) use ($clubId) {
				$query->where('matches.home_team_id', $clubId)
					->orWhere('matches.away_team_id', $clubId);
			});

		if (isset($data['sortby'])) {
			$sortby = $data['sortby'];
			$sorttype = $data['sorttype'];
		} else {
			$sortby = 'matches.id';
			$sorttype = 'desc';
		}
		$matchData = $matchData->orderBy($sortby, $sorttype);

		if (isset($data['competition']) && trim($data['competition']) != '') {
			$matchData->where('matches.competition_id', '=', $data['competition']);
		}
		if (isset($data['opposition']) && trim($data['opposition']) != '') {
			$opposition =  $data['opposition'];

			$matchData->where(function ($query) use ($opposition) {
				$query->where('matches.home_team_id', $opposition)
					->orWhere('matches.away_team_id', $opposition);
			});
		}

		if (!empty($data['from_date'])) {
			$matchData->whereDate('matches.kickoff_time', '>=', convertDateFormat($data['from_date'], config('fanslive.DATE_CMS_FORMAT.php')));
		}

		if (!empty($data['to_date'])) {
			$matchData->whereDate('matches.kickoff_time', '<=', convertDateFormat($data['to_date'], config('fanslive.DATE_CMS_FORMAT.php')));
		}

		$matchListArray = [];
		if (!array_key_exists('pagination', $data)) {
			$matchData = $matchData->paginate($data['pagination_length']);
			$matchListArray = $matchData;
		} else {
			$matchListArray['total'] = $matchData->count();
			$matchListArray['data'] = $matchData->get();
		}

		$response = $matchListArray;

		return $response;
	}

	/**
	 * Handle logic to get matches.
	 *
	 * @param $clubId
	 *
	 * @return mixed
	 */
	public function getCurrentAndFutureMatches($clubId, $matchId)
	{
		$matches = Match::where(function ($query) use ($clubId) {
			$query->where('home_team_id', $clubId)
				->orWhere('away_team_id', $clubId);
		})
			->orderBy('kickoff_time');

		if ($matchId == NULL) {
			$matches = $matches->where('status', 'scheduled')
				->where(DB::raw('CONVERT(kickoff_time, DATE)'), '>=', Carbon::today())
				->get();
		} else {
			$matches = $matches->where(function ($query) use ($matchId) {
				$query->where('id', $matchId)
					->orWhere(function ($q) {
						$q->where(DB::raw('CONVERT(kickoff_time, DATE)'), '>=', Carbon::today())
							->where('status', 'scheduled');
					});
			})->get();
		}
		return $matches;
	}

	/**
	 * Get club's recent match.
	 *
	 * @param $clubId
	 *
	 * @return mixed
	 */
	public function getClubRecentMatch($clubId)
	{
		return Match::where(function ($query) use ($clubId) {
							$query->where('home_team_id', $clubId)
								->orWhere('away_team_id', $clubId);
						})
						->where(DB::raw('CONVERT(kickoff_time, DATE)'), '>=', Carbon::today())
						->where('status', ['scheduled', 'in_play'])
						->orderBy('kickoff_time')
						->first();
	}

	/**
	 * Handle logic to get upcoming matches for hospitality.
	 *
	 * $clubId
	 *
	 * @return mixed
	 */
    public function getUpcomingMatches($clubId)
    {
		return Match::where('is_hospitality_ticket_sale_enabled', true)
					->where('status', 'scheduled')
					->where(function($q)use($clubId){
					        $q->where('home_team_id', '=', $clubId);
					        $q->orWhere('away_team_id', '=', $clubId);
					    })
					->orderBy('kickoff_time', 'asc')
					->get();
    }

    /**
     * Handle logic to get consomer match.
     *
     * @param $consumer
     *
     * @return mixed
     */
    public function getConsumerMatch($consumer, $type, $clubTimings)
    {
    	if($type == "food_and_drink") {
            $startDateTime = Carbon::now()->subMinutes($clubTimings->food_and_drink_minutes_open_before_kickoff)->format("Y-m-d H:i:s");
            $endDateTime = Carbon::now()->addMinutes($clubTimings->food_and_drink_minutes_closed_after_fulltime)->format("Y-m-d H:i:s");
        } elseif($type == 'merchandise') {
            $startDateTime = Carbon::now()->subMinutes($clubTimings->merchandise_minutes_open_before_kickoff)->format("Y-m-d H:i:s");
            $endDateTime = Carbon::now()->addMinutes($clubTimings->merchandise_minutes_closed_after_fulltime)->format("Y-m-d H:i:s");
        } elseif($type == 'loyaltyreward') {
            $startDateTime = Carbon::now()->subMinutes($clubTimings->loyalty_rewards_minutes_open_before_kickoff)->format("Y-m-d H:i:s");
            $endDateTime = Carbon::now()->addMinutes($clubTimings->loyalty_rewards_minutes_closed_after_fulltime)->format("Y-m-d H:i:s");
        }

        return Match::where(function ($query) {
                    $query->where('status', 'scheduled')
                        ->orWhere('status', 'in_play');
                })
                ->where('kickoff_time', '>=', $startDateTime)
                ->where('kickoff_time', '<=', $endDateTime)
                ->where(function ($query) use($consumer) {
                	$query->where('home_team_id', $consumer->club_id)
                		->orWhere('away_team_id', $consumer->club_id);
                })
            	->first();
    }

    /**
     * Handle logic to match is scheduled or not.
     *
     * @param $matchId
     *
     * @return mixed
     */
    public function checkUpcomingMatchById($matchId)
    {
        $match = Match::where('id', $matchId)
						->where(function ($query) {
		                    $query->where('status', 'scheduled')
		                        ->orWhere('status', 'in_play');
		                })
		                ->where(DB::raw('CONVERT(kickoff_time, date)'), '>=', Carbon::today())
		                ->first();

		return $match;
    }
}
