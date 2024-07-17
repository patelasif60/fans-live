<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\LoyaltyRewardService;
use App\Services\ProductService;
use App\Services\EventService;
use App\Services\HospitalitySuiteService;
use App\Services\MembershipPackageService;
use App\Services\TicketService;
use Log;

class UpdateAllQRCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:qrcode';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all QR code.';

     /**
     *  service.
     *
     * @var string
     */
    protected $loyaltyRewardService;
    protected $productService;
    protected $eventService;
    protected $hospitalitySuiteService;
    protected $membershipPackageService;
    protected $ticketService;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(LoyaltyRewardService $loyaltyRewardService,ProductService $productService,EventService $eventService, HospitalitySuiteService $hospitalitySuiteService ,MembershipPackageService $membershipPackageService, TicketService $ticketService)
    {
        parent::__construct();
        $this->loyaltyRewardService          = $loyaltyRewardService;
        $this->productService           = $productService;
        $this->eventService             = $eventService;
        $this->hospitalitySuiteService  = $hospitalitySuiteService;
        $this->membershipPackageService = $membershipPackageService;
        $this->ticketService            = $ticketService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        echo 'Start ticket Process';
        $this->ticketService->uploadQRcode();
        echo 'Start product Process';
        $this->productService->uploadQRcode();
        echo 'Start event Process';
        $this->eventService->uploadQRcode();
        echo 'Start hospitality Process';
        $this->hospitalitySuiteService->uploadQRcode(); 
        echo 'Start membership Process';
        $this->membershipPackageService->uploadQRcode();
        echo 'Start loyalty Reward Process';
        $this->loyaltyRewardService->uploadQRcode();
        echo 'end Upload Process';
    }
}
