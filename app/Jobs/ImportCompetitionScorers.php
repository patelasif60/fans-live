<?php

namespace App\Jobs;

use App\Repositories\ScorerRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportCompetitionScorers implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @var all competition scorers
     */
    protected $allCompetitionScorers;

    /**
     * @var competition id
     */
    protected $competitionId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($allCompetitionScorers, $competitionId)
    {
        $this->allCompetitionScorers = $allCompetitionScorers;
        $this->competitionId = $competitionId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ScorerRepository $scorerRepository)
    {
        info('starting job to import competition scorers');
        // Delete existing scorers
        $scorerRepository->deleteExistingScorers($this->competitionId);

        foreach ($this->allCompetitionScorers['scorers'] as $scorerData) {
            $scorerDetail = [];
            $scorerDetail['competition_id'] = $this->competitionId;
            $scorerDetail['season_id'] = $this->allCompetitionScorers['season']['id'];
            $scorerDetail['player_id'] = $scorerData['player']['id'];
            $scorerDetail['first_name'] = $scorerData['player']['firstName'];
            $scorerDetail['last_name'] = $scorerData['player']['lastName'];
            $scorerDetail['date_of_birth'] = $scorerData['player']['dateOfBirth'];
            $scorerDetail['country_of_birth'] = $scorerData['player']['countryOfBirth'];
            $scorerDetail['nationality'] = $scorerData['player']['nationality'];
            $scorerDetail['position'] = $scorerData['player']['position'];
            $scorerDetail['number_of_goals'] = $scorerData['numberOfGoals'];

            $scorerRepository->create($scorerDetail);
        }
    }
}
