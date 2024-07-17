<?php

namespace App\Console\Commands;

use App\Services\FeedItemService;
use Illuminate\Console\Command;

class GetUpdateFeeds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feed:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all feeds from Facebook, Instagram, Twitter, RSS and Youtube and store into database.';

    /**
     * FeedItem service.
     *
     * @var string
     */
    protected $feedItemService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(FeedItemService $feedItemService)
    {
        parent::__construct();
        $this->feedItemService = $feedItemService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->feedItemService->getUpdateFeeds();
    }
}
