<?php

namespace App\Http\Resources\Match;

use App\Http\Resources\Competition\Competition as CompetitionResource;
use App\Http\Resources\MatchEvent\MatchEvent as MatchEventResource;
use App\Http\Resources\MatchHospitality\MatchHospitality as MatchHospitalityResource;
use App\Http\Resources\MatchPlayer\MatchPlayerCollection as MatchPlayerCollection;
use App\Http\Resources\MatchTicketing\MatchTicketing as MatchTicketingResource;
use App\Http\Resources\Poll\Poll as PollResource;
use App\Models\Consumer;
use App\Repositories\ConsumerRepository;
use App\Repositories\MatchPlayerRepository;
use App\Services\ConsumerService;
use App\Services\MatchPlayerService;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class Match extends JsonResource
{
    /**
     * A consumer user service.
     *
     * @var service
     */
    protected $consumerService;

    /**
     * The consumer instance.
     *
     * @var mixed
     */
    public $consumer;

    /**
     * The accessFrom instance.
     *
     * @var mixed
     */
    public $accessFrom;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct($resource, $consumer = NULL, $accessFrom = NULL)
    {
        parent::__construct($resource);
        $this->consumerService = new ConsumerService(new ConsumerRepository());
        $this->matchPlayerService = new MatchPlayerService(new MatchPlayerRepository());
        $this->consumer = $consumer;
        $this->accessFrom = $accessFrom;
    }

    /**
     * Destory/Unset object variables.
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->consumerService);
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $isMatchButtonDisabled = false;
        $isVotingAvailable = false;
        $matchEndTime = Carbon::parse($this->match_endtime)->addHour();
        if($matchEndTime >= Carbon::now() && $this->status === 'in_play') {
            $isVotingAvailable = true;
        }

        $isPlayerOfTheMatchAvailable = false;
        if($matchEndTime <= Carbon::now() && $this->status == 'finished') {
            $isPlayerOfTheMatchAvailable  = true;
        }

        if ($this->accessFrom == 'console') {
            $consumer = $this->consumer;
        } else {
            $consumer = getLoggedinConsumer();
        }

        $ticketAvailibilityDetail = [];
        if ($this->status === 'scheduled') {
            if($consumer) {
                // Check for consumer membership package and also check for ticket sold out for a match or for a block.
                $ticketAvailibilityDetail = $this->consumerService->checkForMembershipPackageForMatch($consumer, $this->ticketingMembership);

                // Check whether consumer has already sent a notify request
                $isMatchButtonDisabled = $this->consumerService->isAlreadyNotifiedForMatch($this->id, $consumer);
            }
        }

        $homeLineups = $this->getClubPlayersByType($this->home_team_id, 'lineup');
        $awayLineups = $this->getClubPlayersByType($this->away_team_id, 'lineup');

        $isAlreadyVoted = false;

        if($consumer) {
            $checkConsumerVote = $this->matchPlayerService->getConsumerVotingForMatch($consumer->id, $this->id);
            if(isset($checkConsumerVote)) {
                $isAlreadyVoted = true;
            }
        }

        $refereesName = "N/A";
        if($this->referees){
			$refereesArr = json_decode($this->referees);
			$refereesName = implode(', ', array_column($refereesArr,'name'));
		}
        $number_of_tickets_available=null;
        if($this->homeTeam->stadium && !$this->homeTeam->stadium->is_using_allocated_seating)
        {
            $number_of_tickets_available = $this->homeTeam->stadium->number_of_seats - $this->consumerService->getBookedicket($this->id);
        }

        $result = [
            'id'                                 => $this->id,
            'kickoff_time'                       => $this->kickoff_time,
            'winner'                             => $this->winner,
            'half_time_home_team_score'          => $this->half_time_home_team_score,
            'half_time_away_team_score'          => $this->half_time_away_team_score,
            'full_time_home_team_score'          => $this->full_time_home_team_score,
            'full_time_away_team_score'          => $this->full_time_away_team_score,
            'extra_time_home_team_score'         => $this->extra_time_home_team_score,
            'extra_time_away_team_score'         => $this->extra_time_away_team_score,
            'penalties_home_team_score'          => $this->penalties_home_team_score,
            'penalties_away_team_score'          => $this->penalties_away_team_score,
            'status'                             => $this->status,
            'is_published'                       => $this->is_published,
            'is_ticket_sale_enabled'             => $this->is_ticket_sale_enabled,
            'is_hospitality_ticket_sale_enabled' => $this->is_hospitality_ticket_sale_enabled,
            'venue'                              => $this->venue ? $this->venue : 'N/A',
            'attendance'                         => $this->attendance ? strval($this->attendance) : 'N/A',
            'referees'                           => $refereesName,
            'home_team'                          => $this->homeTeam->name,
            'home_team_logo'                     => $this->homeTeam->logo,
            'away_team'                          => $this->awayTeam->name,
            'away_team_logo'                     => $this->awayTeam->logo,
            'away_team_primary_colour'           => $this->awayTeam->primary_colour,
            'away_team_secondary_colour'         => $this->awayTeam->secondary_colour,
            'away_team_primary_colour'           => $this->awayTeam->primary_colour,
            'away_team_secondary_colour'         => $this->awayTeam->secondary_colour,
            'competition'                        => new CompetitionResource($this->competition),
            'home_lineups'                       => MatchPlayerCollection::make($homeLineups)->setMatchId($this->id),
            'away_lineups'                       => MatchPlayerCollection::make($awayLineups)->setMatchId($this->id),
            'home_substitutes'                   => MatchPlayerCollection::make($this->getClubPlayersByType($this->home_team_id, 'bench'))->setMatchId($this->id),
            'away_substitutes'                   => MatchPlayerCollection::make($this->getClubPlayersByType($this->away_team_id, 'bench'))->setMatchId($this->id),
            'events'                             => MatchEventResource::collection($this->event),
            'polls'                              => PollResource::collection($this->poll->where('status', 'Published')->sortByDesc('publication_date')),
            'hospitality'                        => new MatchHospitalityResource($this->hospitality, $this->id, $this->consumer, $this->accessFrom),
            'ticketing'                          => new MatchTicketingResource($this->ticketing),
            'is_already_voted'                   => $isAlreadyVoted,
            'is_player_of_the_match_available'   => $isPlayerOfTheMatchAvailable,
            'is_home_team_match'                 => ($consumer && ($consumer->club_id == $this->homeTeam->id)) ? true : false,
            'number_of_tickets_available'      => $number_of_tickets_available,
        ];

        if ($this->status === 'scheduled') {
            $result = array_merge($result, [
                'is_ticket_available'             => isset($ticketAvailibilityDetail['is_ticket_available']) ? $ticketAvailibilityDetail['is_ticket_available'] : null,
                'ticket_availability_button_text' => isset($ticketAvailibilityDetail['ticket_availability_button_text']) ? $ticketAvailibilityDetail['ticket_availability_button_text'] : null,
                'ticket_unavailibility_reason'    => isset($ticketAvailibilityDetail['ticket_unavailibility_reason']) ? $ticketAvailibilityDetail['ticket_unavailibility_reason'] : null,
                'ticket_availability_message'     => isset($ticketAvailibilityDetail['ticket_availability_message']) ? $ticketAvailibilityDetail['ticket_availability_message'] : null,
                'is_match_button_disabled'        => $isMatchButtonDisabled,
            ]);
        }

        if ($this->status === 'finished') {
            $matchPlayer = $this->matchPlayerService->getPlayerOfTheMatch($this->id);
            $result = array_merge($result, [
                'player_of_the_match' => $matchPlayer ? $matchPlayer->player->name : null,
            ]);
        }

        $result = array_merge($result, [
            'is_overview_available' => true,
            'overview_not_available_message' => null,
            'are_teams_available' => (Carbon::parse($this->kickoff_time)->lessThanOrEqualTo(Carbon::now())) ? (($homeLineups->count() || $awayLineups->count())  ? true : false) : false,
            'teams_not_available_message' => ($this->status === 'finished' && Carbon::parse($this->kickoff_time)->lessThanOrEqualTo(Carbon::now())) ? (($homeLineups->count() || $awayLineups->count())  ? null : 'Data not currently available please check back later') : 'For teams, check back closer to kick off',
            'are_highlights_available' => ( ($this->status === 'finished' || $this->status === 'in_play') && Carbon::parse($this->kickoff_time)->lessThanOrEqualTo(Carbon::now())) ? (($this->event->count()) ? true : false) : false,
            'highlights_not_available_message' => (($this->status === 'finished' || $this->status === 'in_play') && Carbon::parse($this->kickoff_time)->lessThanOrEqualTo(Carbon::now())) ? (($this->event->count()) ? null : 'Data not currently available please check back later') : 'For highlights, check back at kick off',
            'is_voting_available' => $isVotingAvailable,
        ]);

        return $result;
    }
}
