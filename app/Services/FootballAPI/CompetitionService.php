<?php

namespace App\Services\FootballAPI;

use App\Models\Competition;
use App\Models\Match;
use App\Models\Player;
use App\Repositories\ClubRepository;
use App\Repositories\CompetitionRepository;
use App\Repositories\MatchEventRepository;
use App\Repositories\MatchPlayerRepository;
use App\Repositories\MatchRepository;
use App\Repositories\PlayerRepository;
use App\Services\ClubService;
use App\Services\FootballAPI\Client\HttpClient;
use Carbon\Carbon;

/**
 * Class to make request to Football API for getting
 * fixtures and standings competition wise.
 */
class CompetitionService
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
     * The club repository instance.
     *
     * @var clubRepository
     */
    protected $clubRepository;

    /**
     * The player repository instance.
     *
     * @var playerRepository
     */
    protected $playerRepository;

    /**
     * The club service instance.
     *
     * @var clubService
     */
    protected $clubService;

    /**
     * Create a new competition instance.
     *
     * @return void
     */
    public function __construct(MatchRepository $matchRepository, MatchEventRepository $matchEventRepository, MatchPlayerRepository $matchPlayerRepository, CompetitionRepository $competitionRepository, ClubRepository $clubRepository, PlayerRepository $playerRepository, ClubService $clubService)
    {
        $this->client = new Httpclient();
        $this->matchRepository = $matchRepository;
        $this->matchEventRepository = $matchEventRepository;
        $this->matchPlayerRepository = $matchPlayerRepository;
        $this->competitionRepository = $competitionRepository;
        $this->clubRepository = $clubRepository;
        $this->playerRepository = $playerRepository;
        $this->clubService = $clubService;
    }

    /**
     * Destroy a competition instance.
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->client);
        unset($this->matchRepository);
        unset($this->matchEventRepository);
        unset($this->matchPlayerRepository);
        unset($this->competitionRepository);
        unset($this->clubRepository);
        unset($this->playerRepository);
        unset($this->clubService);
    }

    /**
     * Handle logic to get competitions fixtures.
     *
     * @return mixed
     */
    public function getCompetitionsFixtures()
    {
        $dateFrom = Carbon::now('UTC')->subDay(3);//->format('Y-m-d');
        //$dateTo = Carbon::now()->addDays(91)->format('Y-m-d');
        //dd(Carbon::now('UTC')->subDay(3));
        $allPlayers = Player::all()->keyBy('player_api_id');
        $competitionsWithAppId = $this->competitionRepository->getCompetitionsWithAppId();
        foreach ($competitionsWithAppId as $competition) {
            $result = $this->client->get('/v2/competitions/'.$competition->external_app_id.'/matches', [], []);
            $allCompetitionFixtures = json_decode($result, true);
            //dd($allCompetitionFixtures);
            foreach ($allCompetitionFixtures['matches'] as $match) {
                $matchDetails = [];
                if($dateFrom <= Carbon::parse($match['utcDate'], 'UTC'))
                {
                    $existingMatch = $this->matchRepository->getMatchById($match['id']);
                    if ($existingMatch) {
                        $existingMatch->kickoff_time = Carbon::parse($match['utcDate'], 'UTC');
                        $existingMatch->save();
                        continue;
                    }

                    $matchDetails['api_id'] = $match['id'];
                    $matchDetails['competition_id'] = $competition->id;
                    $matchDetails['status'] = $match['status'];
                    $matchDetails['minute'] = isset($match['minute']) ? $match['minute'] : null;
                    $matchDetails['attendance'] = isset($match['attendance']) ? $match['attendance'] : null;
                    $matchDetails['stage'] = $match['stage'];
                    $matchDetails['matchday'] = $match['matchday'];
                    $matchDetails['group'] = $match['group'];
                    $matchDetails['last_updated'] = Carbon::parse($match['lastUpdated'], 'UTC');
                    $matchDetails['kickoff_time'] = Carbon::parse($match['utcDate'], 'UTC');
                    $matchDetails['is_match_imported'] = true;
                    $matchDetails['duration'] = $match['score']['duration'];
                    $matchDetails['full_time_home_team_score'] = $match['score']['fullTime']['homeTeam'];
                    $matchDetails['full_time_away_team_score'] = $match['score']['fullTime']['awayTeam'];
                    $matchDetails['half_time_home_team_score'] = $match['score']['halfTime']['homeTeam'];
                    $matchDetails['half_time_away_team_score'] = $match['score']['halfTime']['awayTeam'];
                    $matchDetails['extra_time_home_team_score'] = $match['score']['extraTime']['homeTeam'];
                    $matchDetails['extra_time_away_team_score'] = $match['score']['extraTime']['awayTeam'];
                    $matchDetails['penalties_home_team_score'] = $match['score']['penalties']['homeTeam'];
                    $matchDetails['penalties_away_team_score'] = $match['score']['penalties']['awayTeam'];
                    $matchDetails['home_team_id'] = $this->getClubId($match['homeTeam']['id']);
                    $matchDetails['away_team_id'] = $this->getClubId($match['awayTeam']['id']);
                    $matchDetails['referees'] = json_encode($match['referees']);
                    $matchDetails['is_published'] = true;
                    if ($match['score']['winner'] == 'HOME_TEAM') {
                        $matchDetails['winner'] = $matchDetails['home_team_id'];
                    } elseif ($match['score']['winner'] == 'AWAY_TEAM') {
                        $matchDetails['winner'] = $matchDetails['away_team_id'];
                    } else {
                        $matchDetails['winner'] = null;
                    }

                    $matchData = $this->matchRepository->create($matchDetails);

                    if (isset($match['homeTeam']['lineup'])) {
                        $this->createMatchPlayer($match['homeTeam']['lineup'], $matchData->id, $match['homeTeam']['id'], 'lineup', $allPlayers);
                    }

                    if (isset($match['homeTeam']['bench'])) {
                        $this->createMatchPlayer($match['homeTeam']['bench'], $matchData->id, $match['homeTeam']['id'], 'bench', $allPlayers);
                    }

                    if (isset($match['awayTeam']['lineup'])) {
                        $this->createMatchPlayer($match['awayTeam']['lineup'], $matchData->id, $match['awayTeam']['id'], 'lineup', $allPlayers);
                    }

                    if (isset($match['awayTeam']['bench'])) {
                        $this->createMatchPlayer($match['awayTeam']['bench'], $matchData->id, $match['awayTeam']['id'], 'bench', $allPlayers);
                    }

                    if (isset($match['goals'])) {
                        $this->createMatchEvent($match['goals'], $matchData->id, 'goal', $allPlayers);
                    }

                    if (isset($match['bookings'])) {
                        $this->createMatchEvent($match['bookings'], $matchData->id, 'booking', $allPlayers);
                    }

                    if (isset($match['substitutions'])) {
                        $this->createMatchEvent($match['substitutions'], $matchData->id, 'substitution', $allPlayers);
                    }
                    sleep(1);
                }
            }
        }
    }

    /**
     * Handle logic to create match player.
     *
     * @param $players
     * @param $matchId
     * @param $teamId
     * @param $type
     * @param $allPlayers
     *
     * @return mixed
     */
    public function createMatchPlayer($players, $matchId, $teamId, $type, $allPlayers)
    {
        $matchPlayerData = [];
        if (!empty($players)) {
            foreach ($players as $player) {
                $matchPlayerData['player_id'] = $this->getPlayerId($player, $allPlayers);
                $matchPlayerData['match_id'] = $matchId;
                $matchPlayerData['club_id'] = $this->getClubId($teamId);
                $matchPlayerData['position'] = $player['position'];
                $matchPlayerData['shirt_number'] = $player['shirtNumber'];
                $matchPlayerData['type'] = $type;
                $matchPlayerData['is_substitute'] = $type === 'lineup' ? false : true;

                $this->matchPlayerRepository->create($matchPlayerData);
            }
        }
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
                $matchEventData['club_id'] = $this->getClubId($event['team']['id']);
                $matchEventData['event_type'] = $type == 'booking' ? $event['card'] : $type;
                if ($type == 'goal') {
                    $matchEventData['player_id'] = $allPlayers[$event['scorer']['id']]->id;
                } elseif ($type == 'booking') {
                    $matchEventData['player_id'] = $allPlayers[$event['player']['id']]->id;
                } else {
                    $matchEventData['player_id'] = $allPlayers[$event['playerOut']['id']]->id;
                    $matchEventData['substitute_player_id'] = $allPlayers[$event['playerIn']['id']]->id;
                }
                $matchEventData['minute'] = $event['minute'];

                $this->matchEventRepository->create($matchEventData);
            }
        }
    }

    /**
     * Handle logic to get club id.
     *
     * @param $teamId
     *
     * @return mixed
     */
    public function getClubId($teamId)
    {
        $getClub = $this->clubRepository->getClub($teamId);
        if ($getClub) {
            return $getClub->id;
        } else {
            $club = $this->clubService->createClub($teamId);

            return $club->id;
        }
    }

    /**
     * Handle logic to get player id.
     *
     * @param $player
     * @param $allPlayers
     *
     * @return mixed
     */
    public function getPlayerId($player, $allPlayers)
    {
        $getPlayer = $this->playerRepository->getPlayer($player['id']);
        if ($getPlayer) {
            return $getPlayer->id;
        } else {
            $playerData = [];
            $playerData['player_api_id'] = $player['id'];
            $playerData['name'] = $player['name'];
            $newPlayer = $this->playerRepository->create($playerData);
            $allPlayers[$player['id']] = $newPlayer;

            return $newPlayer->id;
        }
    }
}
