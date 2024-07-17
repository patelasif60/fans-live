<?php

namespace App\Http\Resources\Match;

use App\Http\Resources\MatchTicketingSponsor\MatchTicketingSponsor as MatchTicketingSponsorResource;
use App\Http\Resources\Competition\Competition as CompetitionResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Services\ConsumerService;
use App\Repositories\ConsumerRepository;

class MatchBrief extends JsonResource
{
        /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->consumerService = new ConsumerService(new ConsumerRepository());
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $number_of_tickets_available=null;
        if(!$this->homeTeam->stadium->is_using_allocated_seating)
        {
            $number_of_tickets_available = $this->homeTeam->stadium->number_of_seats - $this->consumerService->getBookedicket($this->id);
        }
        $refereesName = "N/A";
        if($this->referees){
            $refereesArr = json_decode($this->referees);
            $refereesName = implode(', ', array_column($refereesArr,'name'));
        }
        return [
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
            'match_sponsors'        => ($this->ticketing && $this->ticketing->sponsor) ? MatchTicketingSponsorResource::collection($this->ticketing->sponsor) : null,
            'allow_ticket_returns_resales' => $this->ticketing ? $this->ticketing->allow_ticket_returns_resales : 0,
            'number_of_tickets_available'      => $number_of_tickets_available,
        ];

    }
}
