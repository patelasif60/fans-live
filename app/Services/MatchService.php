<?php

namespace App\Services;

use App\Repositories\MatchEventRepository;
use App\Repositories\MatchPlayerRepository;
use App\Repositories\MatchRepository;
use App\Repositories\PlayerRepository;
use App\Models\MatchHospitality;
use App\Models\MatchTicketing;
use Carbon\Carbon;
use Illuminate\Support\Arr;

/**
 * User class to handle operator interactions.
 */
class MatchService
{
    /**
     * The match repository instance.
     *
     * @var matchRepository
     */
    private $matchRepository;

    /**
     * The match event repository instance.
     *
     * @var repository
     */
    protected $matchEventService;
    protected $matchHospitalityService;
    protected $matchEventRepository;

    protected $videoPath;

    /**
     * The match player repository instance.
     *
     * @var repository
     */
    protected $matchPlayerRepository;

    /**
     * The match ticketing service instance.
     *
     * @var matchTicketingService
     */
    protected $matchTicketingService;

    /**
     * The player repository instance.
     *
     * @var playerRepository
     */
    protected $playerRepository;

    /**
     * Create a new service instance.
     *
     * @param MatchRepository $matchRepository
     */
    public function __construct(MatchRepository $matchRepository, MatchEventRepository $matchEventRepository, MatchPlayerRepository $matchPlayerRepository, MatchEventService $matchEventService, MatchTicketingService $matchTicketingService, MatchHospitalityService $matchHospitalityService, PlayerRepository $playerRepository)
    {
        $this->videoPath = config('fanslive.VIDEOPATH.match_event_video');
        $this->matchRepository = $matchRepository;
        $this->matchEventRepository = $matchEventRepository;
        $this->matchPlayerRepository = $matchPlayerRepository;
        $this->matchEventService = $matchEventService;
        $this->matchHospitalityService = $matchHospitalityService;
        $this->matchTicketingService = $matchTicketingService;
        $this->playerRepository = $playerRepository;
    }

    /**
     * Destory/Unset object variables.
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->videoPath);
        unset($this->matchRepository);
        unset($this->matchEventRepository);
        unset($this->matchPlayerRepository);
        unset($this->matchEventService);
        unset($this->matchHospitalityService);
        unset($this->matchTicketingService);
        unset($this->playerRepository);
    }

    /**
     * Handle logic to create a membership package.
     *
     * @param $clubId
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($data)
    {
        $matchDetail = [];
        $matchDetail['competition_id'] = '1';
        $matchDetail['home_team_id'] = $data['home'];
        $matchDetail['away_team_id'] = $data['away'];
        $matchDetail['kickoff_time'] = convertDateTimezone($data['kickoff_time'], $data['global_club_timezone'], null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php'));
        $matchDetail['full_time_home_team_score'] = $data['result_home'];
        $matchDetail['full_time_away_team_score'] = $data['result_away'];
        $matchDetail['extra_time_home_team_score'] = $data['aet_home'];
        $matchDetail['extra_time_away_team_score'] = $data['aet_home'];
        $matchDetail['penalties_home_team_score'] = $data['penalties_home'];
        $matchDetail['penalties_away_team_score'] = $data['penalties_away'];
        $matchDetail['status'] = 'scheduled';
        $matchDetail['is_match_imported'] = false;
        $matchDetail['is_published'] = isset($data['is_published']) ? $data['is_published'] : 0;
        $matchDetail['last_updated'] = Carbon::now()->format('Y-m-d H:i:s');
        $matchDetail['is_ticket_sale_enabled'] = isset($data['is_enable_ticket']) ? $data['is_enable_ticket'] : 0;
        $matchDetail['is_hospitality_ticket_sale_enabled'] = isset($data['is_enable_hospitality']) ? $data['is_enable_hospitality'] : 0;

        $matchPackage = $this->matchRepository->create($matchDetail);

        // if (!empty($data['line_ups_home_number'])) {
        //     foreach ($data['line_ups_home_number'] as $key => $player) {
        //         $matchPlayerData = [];
        //         $matchPlayerData['player_id'] = $data['line_ups_home_name'][$key];
        //         $matchPlayerData['match_id'] = $matchPackage->id;
        //         $matchPlayerData['club_id'] = $matchPackage->home_team_id;
        //         $matchPlayerData['type'] = !empty($data['sub_home'][$key]) == 1 ? 'bench' : 'lineup';
        //         $matchPlayerData['shirt_number'] = $data['line_ups_home_number'][$key];
        //         $matchPlayerData['is_substitute'] = !empty($data['sub_home'][$key]);

        //         $this->matchPlayerRepository->create($matchPlayerData);
        //     }
        // }

        // if (!empty($data['line_ups_away_number'])) {
        //     foreach ($data['line_ups_away_number'] as $key => $player) {
        //         $matchPlayerData['player_id'] = $data['line_ups_away_name'][$key];
        //         $matchPlayerData['match_id'] = $matchPackage->id;
        //         $matchPlayerData['club_id'] = $matchPackage->away_team_id;
        //         $matchPlayerData['type'] = !empty($data['sub_away'][$key]) == 1 ? 'bench' : 'lineup';
        //         $matchPlayerData['shirt_number'] = $data['line_ups_away_number'][$key];
        //         $matchPlayerData['is_substitute'] = !empty($data['sub_away'][$key]);

        //         $this->matchPlayerRepository->create($matchPlayerData);
        //     }
        // }

        if (Arr::get($data, 'is_enable_hospitality')) {
            $matchHospitality = $this->matchHospitalityService->create($matchPackage->id, $data);
        }

        if (isset($data['is_enable_ticket'])) {
            $matchTicketing = $this->matchTicketingService->create($matchPackage->id, $data);
        }

        return $matchPackage;
    }

    /**
     * Handle logic to update a given membership package.
     *
     * @param $user
     * @param $membershipPackage
     * @param $data
     *
     * @return mixed
     */
    public function update($match, $data, $club)
    {
        if($match->is_match_imported)
        {
            $home = $match->home_team_id;
            $away = $match->away_team_id;
        }
        else
        {
            $home = $data['home'];
            $away = $data['away'];
        }

        $matchDetail = [];
        $matchDetail['home_team_id'] = $home;
        $matchDetail['away_team_id'] = $away;
        $matchDetail['status'] = 'scheduled';
        // $matchDetail['kickoff_time'] = $match->is_match_imported == 1 ? convertDateTimezone($match->kickoff_time, $data['global_club_timezone'], null, null, null) : convertDateTimezone($data['kickoff_time'], $data['global_club_timezone'], null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php'));
        $matchDetail['kickoff_time'] = convertDateTimezone($data['kickoff_time'], $data['global_club_timezone'], null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php'));

        $matchDetail['full_time_home_team_score'] = $data['result_home'];
        $matchDetail['full_time_away_team_score'] = $data['result_away'];
        $matchDetail['extra_time_home_team_score'] = $data['aet_home'];
        $matchDetail['extra_time_away_team_score'] = $data['aet_away'];
        $matchDetail['penalties_home_team_score'] = $data['penalties_home'];
        $matchDetail['penalties_away_team_score'] = $data['penalties_away'];
        $matchDetail['is_published'] = Arr::get($data, 'is_published', 0);
        $matchDetail['last_updated'] = Carbon::now()->format('Y-m-d H:i:s');
        $matchDetail['is_ticket_sale_enabled'] = isset($data['is_enable_ticket']) ? $data['is_enable_ticket'] : 0;
        $matchDetail['is_hospitality_ticket_sale_enabled'] = isset($data['is_enable_hospitality']) ? $data['is_enable_hospitality'] : 0;

        $matchEventResponse = $this->matchEventService->update($match->id, $data);

        $matchToUpdate = $this->matchRepository->updateMatchDetail($match, $matchDetail);

        
        if (isset($data['is_enable_ticket']) && ($home == $club)) {
            $matchTicketing = $this->matchTicketingService->update($match->id, $data);
        } else {
            $matchTicketing = $this->matchTicketingService->delete($match->id);
        }

        if (isset($data['is_enable_hospitality']) && ($home == $club)) {
            $matchHospitality = MatchHospitality::where('match_id', $match->id)->first();
            if ($match->is_hospitality_ticket_sale_enabled && $matchHospitality) {
                $matchHospitality = $this->matchHospitalityService->update($match->id, $data);
            } else {
                $matchHospitality = $this->matchHospitalityService->create($match->id, $data);
            }
        } else {
            $matchHospitality = $this->matchHospitalityService->delete($match->id);
        }

        return $matchToUpdate;
    }

    /**
     * Handle logic to delete a given image file.
     *
     * @param $matchId
     *
     * @return mixed
     */
    public function deleteImage($matchId)
    {
        $matchTicketingImages = $this->matchTicketingService->deleteImage($matchId);

        return $matchTicketingImages;
    }

    /**
     * Get membership package user data.
     *
     * @param $data
     * @param $clubId
     *
     * @return mixed
     */
    public function getData($data, $clubId)
    {
        $matchData = $this->matchRepository->getData($data, $clubId);

        return $matchData;
    }

    /**
     * Add player.
     *
     * @param $data
     *
     * @return mixed
     */
    public function addPlayer($data)
    {
        return $this->playerRepository->create($data);
    }

    /**
     * Handle logic to get matches.
     *
     * @param $clubId
     *
     * @return mixed
     */
    public function getCurrentAndFutureMatches($clubId, $matchId = NULL)
    {
        return $this->matchRepository->getCurrentAndFutureMatches($clubId, $matchId);
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
        return $this->matchRepository->getClubRecentMatch($clubId);
    }

    /**
     * Handle logic to get upcoming matches for hospitality.
     *
     * @param $clubId
     *
     * @return mixed
     */
    public function getUpcomingMatches($clubId)
    {
        return $this->matchRepository->getUpcomingMatches($clubId);
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
        return $this->matchRepository->getConsumerMatch($consumer, $type, $clubTimings);
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
        return $this->matchRepository->checkUpcomingMatchById($matchId);
    }
}
