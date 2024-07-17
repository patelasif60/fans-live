<?php

namespace App\Services\FootballAPI;

use App\Jobs\ImportCompetitionScorers;
use App\Jobs\ImportCompetitionStandings;
use App\Models\Competition;
use App\Models\Match;
use App\Models\MatchPlayer;
use App\Models\Player;
use App\Repositories\ClubRepository;
use App\Repositories\MatchEventRepository;
use App\Repositories\MatchPlayerRepository;
use App\Repositories\MatchRepository;
use App\Repositories\PlayerRepository;
use App\Repositories\StandingRepository;
use App\Services\ClubService;
use App\Services\FootballAPI\Client\HttpClient;

/**
 * Class to make request to Football API for updating match detail.
 */
class MatchService
{
    /**
     * The match repository instance.
     *
     * @var repository
     */
    protected $matchRepository;

    /**
     * The match event repository instance.
     *
     * @var repository
     */
    protected $matchEventRepository;

    /**
     * The match player repository instance.
     *
     * @var repository
     */
    protected $matchPlayerRepository;

    /**
     * The player repository instance.
     *
     * @var playerRepository
     */
    protected $playerRepository;

    /**
     * The club repository instance.
     *
     * @var clubRepository
     */
    protected $clubRepository;

    /**
     * The club service instance.
     *
     * @var clubService
     */
    protected $clubService;

    /**
     * Create a new match instance.
     *
     * @return void
     */
    public function __construct(MatchRepository $matchRepository, MatchEventRepository $matchEventRepository, MatchPlayerRepository $matchPlayerRepository, StandingRepository $standingRepository, PlayerRepository $playerRepository, ClubRepository $clubRepository, ClubService $clubService)
    {
        $this->client = new Httpclient();
        $this->matchRepository = $matchRepository;
        $this->matchEventRepository = $matchEventRepository;
        $this->matchPlayerRepository = $matchPlayerRepository;
        $this->standingRepository = $standingRepository;
        $this->playerRepository = $playerRepository;
        $this->clubRepository = $clubRepository;
        $this->clubService = $clubService;
    }

    /**
     * Destroy a match instance.
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->client);
        unset($this->matchRepository);
        unset($this->matchEventRepository);
        unset($this->matchPlayerRepository);
        unset($this->standingRepository);
        unset($this->playerRepository);
        unset($this->clubRepository);
        unset($this->clubService);
    }

    /**
     * Handle logic to update match detail.
     *
     * @return mixed
     */
    public function updateMatchDetail($isAllFlag, $competitionId)
    {
    	$allMatches = [];
    	if($isAllFlag) {
    		$allMatches = $this->matchRepository->getAllUnfinishedMatches();
    	} elseif ($competitionId) {
    		$allMatches = $this->matchRepository->getCompetitionMatches($competitionId);
    	} else {
    		$allMatches = $this->matchRepository->getMatchesWithinRange();
    	}

        $competitions = collect();
        $allPlayers = Player::all()->keyBy('player_api_id');
        foreach ($allMatches as $match) {
            $result = $this->client->get('/v2/matches/'.$match->api_id);
            $matchData = json_decode($result, true);

            $homeClub = $this->clubRepository->getClub($matchData['match']['homeTeam']['id']);
            if (!$homeClub) {
                $homeClub = $this->clubService->createClub($matchData['match']['homeTeam']['id']);
            }
            $awayClub = $this->clubRepository->getClub($matchData['match']['awayTeam']['id']);
            if (!$awayClub) {
                $awayClub = $this->clubService->createClub($matchData['match']['awayTeam']['id']);
            }
            $existingMatch = $this->matchRepository->getMatchById($match->id);
            $matchDetails = [];
            if($existingMatch && $existingMatch->status!="finished" && $matchData['match']['status'] == 'finished') {
                $matchDetails['match_endtime'] = Carbon::parse(now(), 'UTC');
            }
            $matchDetails['status'] = $matchData['match']['status'];
            $matchDetails['attendance'] = isset($matchData['match']['attendance']) ? $matchData['match']['attendance'] : null;
            $matchDetails['venue'] = $matchData['match']['venue'];
            $matchDetails['referees'] = json_encode($matchData['match']['referees']);
            $matchDetails['duration'] = $matchData['match']['score']['duration'];
            $matchDetails['full_time_home_team_score'] = $matchData['match']['score']['fullTime']['homeTeam'];
            $matchDetails['full_time_away_team_score'] = $matchData['match']['score']['fullTime']['awayTeam'];
            $matchDetails['half_time_home_team_score'] = $matchData['match']['score']['halfTime']['homeTeam'];
            $matchDetails['half_time_away_team_score'] = $matchData['match']['score']['halfTime']['awayTeam'];
            $matchDetails['extra_time_home_team_score'] = $matchData['match']['score']['extraTime']['homeTeam'];
            $matchDetails['extra_time_away_team_score'] = $matchData['match']['score']['extraTime']['awayTeam'];
            $matchDetails['penalties_home_team_score'] = $matchData['match']['score']['penalties']['homeTeam'];
            $matchDetails['penalties_away_team_score'] = $matchData['match']['score']['penalties']['awayTeam'];
            if ($matchData['match']['score']['winner'] == 'HOME_TEAM') {
                $matchDetails['winner'] = $homeClub->id;
            } elseif ($matchData['match']['score']['winner'] == 'AWAY_TEAM') {
                $matchDetails['winner'] = $awayClub->id;
            } else {
                $matchDetails['winner'] = null;
            }

            $this->matchRepository->updateMatchDetail($match, $matchDetails);

            $this->manageMatchPlayer($matchData['match']['homeTeam']['lineup'], $match, $homeClub, 'lineup', $allPlayers);

            $this->manageMatchPlayer($matchData['match']['homeTeam']['bench'], $match, $homeClub, 'bench', $allPlayers);

            $this->manageMatchPlayer($matchData['match']['awayTeam']['lineup'], $match, $awayClub, 'lineup', $allPlayers);

            $this->manageMatchPlayer($matchData['match']['awayTeam']['bench'], $match, $awayClub, 'bench', $allPlayers);

            $this->matchEventRepository->deleteEvent($match->id);

            $this->createMatchEvent($matchData['match']['goals'], $match->id, 'goal', $allPlayers);

            $this->createMatchEvent($matchData['match']['bookings'], $match->id, 'booking', $allPlayers);

            $this->createMatchEvent($matchData['match']['substitutions'], $match->id, 'substitution', $allPlayers);

            if ($match->status == 'finished') {
                if (!$competitions->contains('competition_id', $match->competition_id)) {
                    $competitions[] = ['competition_id' => $match->competition_id, 'api_competition_id' => $match->competition->external_app_id];
                }
            }
            sleep(3);
        }

        foreach ($competitions as $competition) {
            $competitionResult = $this->client->get('/v2/competitions/'.$competition['api_competition_id'].'/standings');
            $allCompetitionStandings = json_decode($competitionResult, true);
            ImportCompetitionStandings::dispatch($allCompetitionStandings, $competition['competition_id']);

            sleep(3);

            /*$scorerResult = $this->client->get('/v2/competitions/'. $competition['api_competition_id'] .'/scorers', [], ['limit' => 200]);
            $allCompetitionScorers = json_decode($scorerResult, true);
            ImportCompetitionScorers::dispatch($allCompetitionScorers, $competition['competition_id']);*/
        }
    }

    /**
     * Manage match player.
     *
     * @param $players
     * @param $match
     * @param $team
     * @param $type
     * @param $allPlayers
     *
     * @return mixed
     */
    public function manageMatchPlayer($players, $match, $team, $type, $allPlayers)
    {
        if (!empty($players)) {
            $matchPlayers = MatchPlayer::where('match_id', $match->id)->where('club_id', $team->id)->where('type', $type)->get()->keyBy('id')->toArray();
            $playersIds = array_column($matchPlayers, 'player_id');
            $unProcessedMatchPlayerIds = array_keys($matchPlayers);
            foreach ($players as $player) {
                $matchPlayerDetail = $this->processMatchPlayer($player, $match, $team, $playersIds, $type, $allPlayers);
                if ($matchPlayerDetail['isMatchPlayerAlreadyExist']) {
                    unset($unProcessedMatchPlayerIds[$matchPlayerDetail['detail']->id]);
                }
            }
            if (!empty($unProcessedMatchPlayerIds)) {
                $this->matchPlayerRepository->deleteAll(array_values($unProcessedMatchPlayerIds));
            }
        } else {
            MatchPlayer::where('match_id', $match->id)->where('club_id', $team->id)->where('type', $type)->delete();
        }
    }

    /**
     * Process match player.
     *
     * @return mixed
     */
    public function processMatchPlayer($player, $match, $club, $playersIds, $type, $allPlayers)
    {
        $matchPlayerData = [];
        $isMatchPlayerAlreadyExist = true;
        $dbPlayerId = null;

        $dbPlayerId = isset($allPlayers[$player['id']]) ? $allPlayers[$player['id']]->id : null;

        if (!$dbPlayerId) {
            $playerData = [];
            $playerData['player_api_id'] = $player['id'];
            $playerData['name'] = $player['name'];
            $dbPlayer = $this->playerRepository->create($playerData);
            $allPlayers[$player['id']] = $dbPlayer;
            $dbPlayerId = $dbPlayer->id;
        }

        if (($key = array_search($dbPlayerId, $playersIds)) !== false) {
            $matchPlayerConditions = [];
            $matchPlayerData['position'] = $player['position'];
            $matchPlayerData['shirt_number'] = $player['shirtNumber'];
            $matchPlayerConditions['player_id'] = $dbPlayerId;
            $matchPlayerConditions['club_id'] = $club->id;
            $matchPlayerConditions['match_id'] = $match->id;

            $matchPlayer = $this->matchPlayerRepository->update($matchPlayerData, $matchPlayerConditions);
        } else {
            $isMatchPlayerAlreadyExist = false;
            $matchPlayerData['player_id'] = $dbPlayerId;
            $matchPlayerData['match_id'] = $match->id;
            $matchPlayerData['club_id'] = $club->id;
            $matchPlayerData['type'] = $type;
            $matchPlayerData['position'] = $player['position'];
            $matchPlayerData['shirt_number'] = $player['shirtNumber'];
            $matchPlayerData['is_substitute'] = $type === 'lineup' ? false : true;

            $matchPlayer = $this->matchPlayerRepository->create($matchPlayerData);
        }

        return ['isMatchPlayerAlreadyExist' => $isMatchPlayerAlreadyExist, 'detail' => $matchPlayer];
    }

    /**
     * Handle logic to create match event.
     *
     * @param $events
     * @param $matchId
     * @param $type
     * @param $allPlayers
     *
     * @return mixed
     */
    public function createMatchEvent($events, $matchId, $type, $allPlayers)
    {
        $matchEventData = [];
        if (!empty($events)) {
            foreach ($events as $event) {
                $matchEventData['match_id'] = $matchId;

                $club = $this->clubRepository->getClub($event['team']['id']);
                if (!$club) {
                    $club = $this->clubService->createClub($event['team']['id']);
                }
                $matchEventData['club_id'] = $club->id;
                $matchEventData['event_type'] = $type == 'booking' ? $event['card'] : $type;
                $matchEventData['minute'] = $event['minute'];
                if ($type == 'goal') {
                    $matchEventData['player_id'] = $allPlayers[$event['scorer']['id']]->id;
                    $matchEventData['extra_time'] = $event['extraTime'];
                } elseif ($type == 'booking') {
                    $matchEventData['player_id'] = $allPlayers[$event['player']['id']]->id;
                } else {
                    $matchEventData['player_id'] = $allPlayers[$event['playerOut']['id']]->id;
                    $matchEventData['substitute_player_id'] = $allPlayers[$event['playerIn']['id']]->id;
                }
                $this->matchEventRepository->create($matchEventData);
                sleep(2);
            }
        }
    }
}
