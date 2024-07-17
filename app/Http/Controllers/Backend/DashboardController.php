<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Services\ClubCategoryService;
use App\Services\ClubService;
use App\Services\CompetitionService;
use App\Services\TransactionService;
use App\Services\UserService;
use App\Models\Club;
use DB;

class DashboardController extends Controller
{
    /**
     * create service instance
     *
     */
    protected $transactionService;
	protected $userService;
	protected $clubCategoryService;
	protected $competitionService;
	protected $clubService;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(TransactionService $transactionService, UserService $userService, ClubCategoryService $clubCategoryService, CompetitionService $competitionService, ClubService $clubService)
	{
		$this->middleware('auth');
        $this->transactionService = $transactionService;
        $this->userService = $userService;
        $this->clubCategoryService = $clubCategoryService;
        $this->competitionService = $competitionService;
        $this->clubService = $clubService;
	}

	/**
	 * Show the super admin dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function showSuperAdminDashboard()
	{
		$userCount = $this->userService->getUsersForDashboard();
		$clubCategoryCount = $this->clubCategoryService->getClubCategoryCount();
		$competitionCount = $this->competitionService->getCompetitionCount();
		$clubCount = $this->clubService->getClubCount();
		$gbpTransactionSum = $this->transactionService->getGbpTransactionSum();
		$eurTransactionSum = $this->transactionService->getEurTransactionSum();

		$total = $gbpTransactionSum + $eurTransactionSum;

		return view('backend.dashboard', compact('userCount', 'clubCategoryCount', 'competitionCount', 'clubCount', 'gbpTransactionSum', 'eurTransactionSum', 'total'));
	}

	/**
	 * Show the club admin dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function showClubAdminDashboard($club)
	{
		$clubId = getClubIdBySlug($club);
        $clubInfo = Club::findOrFail($clubId);
        $users = $this->userService->getUsersForDashboard($clubId);
        $transactionsCount = $this->transactionService->getTransactionsForDashboard($clubId, 'count');
        $transactionsPriceTotal = $this->transactionService->getTransactionsForDashboard($clubId, 'price_total');
        return view('backend.clubdashboard',compact('transactionsCount','transactionsPriceTotal','clubInfo','users'));
	}
}
