<?php

namespace App\Console\Commands;

use App\Services\FootballAPI\MatchService;
use Illuminate\Console\Command;

class UpdateMatchDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'footballapi:update-match-details {--all : Whether to update all matches} {--competition= : Whether to update matches of particular competition}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get close to real-time data of match';

    /**
     * Match service.
     *
     * @var string
     */
    protected $matchService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(MatchService $matchService)
    {
        parent::__construct();
        $this->matchService = $matchService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	// Retrieve a all option.
		$isAllFlag = $this->option('all');
		$competitionId = $this->option('competition');
		$this->matchService->updateMatchDetail($isAllFlag, $competitionId);
    }
}
