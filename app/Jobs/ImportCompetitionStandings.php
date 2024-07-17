<?php

namespace App\Jobs;

use App\Repositories\ClubRepository;
use App\Repositories\StandingRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportCompetitionStandings implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @var all competition standings
     */
    protected $allCompetitionStandings;

    /**
     * @var competition id
     */
    protected $competitionId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($allCompetitionStandings, $competitionId)
    {
        $this->allCompetitionStandings = $allCompetitionStandings;
        $this->competitionId = $competitionId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(StandingRepository $standingRepository, ClubRepository $clubRepository)
    {
        info('starting job to import competition standings');
        // Delete existing standings
        $standingRepository->deleteExistingStandings($this->competitionId);

        foreach ($this->allCompetitionStandings['standings'] as $standing) {
            foreach ($standing['table'] as $standingData) {
                $standingDetail = [];
                $standingDetail['competition_id'] = $this->competitionId;
                $standingDetail['stage'] = $standing['stage'];
                $standingDetail['type'] = $standing['type'];
                $standingDetail['group'] = $standing['group'];
                $standingDetail['position'] = $standingData['position'];

                $getClub = $clubRepository->getClub($standingData['team']['id']);
                if ($getClub) {
                    $standingDetail['club_id'] = $getClub->id;
                } else {
                    $clubTeamData = $this->client->get('/v2/teams/'.$standingData['team']['id']);
                    $clubTeam = json_decode($clubTeamData, true);
                    $clubTeamArray = [];
                    $clubTeamArray['name'] = $clubTeam['name'];
                    $clubTeamArray['logo'] = $clubTeam['crestUrl'];
                    $clubTeamArray['logo_file_name'] = basename($clubTeam['crestUrl']);
                    $clubTeamArray['status'] = 'Hidden';
                    $clubTeamArray['external_app_id'] = $standingData['team']['id'];
                    $club = $clubRepository->createClub($clubTeamArray);

                    $standingDetail['club_id'] = $club->id;
                }

                $standingDetail['played_games'] = $standingData['playedGames'];
                $standingDetail['won'] = $standingData['won'];
                $standingDetail['draw'] = $standingData['draw'];
                $standingDetail['lost'] = $standingData['lost'];
                $standingDetail['points'] = $standingData['points'];
                $standingDetail['goal_for'] = $standingData['goalsFor'];
                $standingDetail['goal_against'] = $standingData['goalsAgainst'];
                $standingDetail['goal_difference'] = $standingData['goalDifference'];

                $standingRepository->create($standingDetail);
            }
        }
    }
}
