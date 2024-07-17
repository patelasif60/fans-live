<?php

namespace App\Console\Commands;

use App\Services\FootballAPI\CompetitionService;
use Illuminate\Console\Command;

class GetCompetitionsFixtures extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'footballapi:get-competitions-fixtures';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get list of matches for each competitions';

    /**
     * Competition service.
     *
     * @var string
     */
    protected $competitionService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CompetitionService $competitionService)
    {
        parent::__construct();
        $this->competitionService = $competitionService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->competitionService->getCompetitionsFixtures();
    }
}
