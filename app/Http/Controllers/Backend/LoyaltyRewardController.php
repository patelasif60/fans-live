<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoyaltyReward\StoreRequest;
use App\Http\Requests\LoyaltyReward\UpdateRequest;
use App\Models\LoyaltyReward;
use App\Services\LoyaltyRewardService;
use App\Services\CollectionPointService;
use Illuminate\Http\Request;

class LoyaltyRewardController extends Controller
{
    /**
     * The Product service instance.
     *
     * @var service
     */
    public function __construct(LoyaltyRewardService $service, CollectionPointService $collectionPointService)
    {
        $this->service = $service;
        $this->collectionPointService = $collectionPointService;
    }

    /**
     * unset class instance.
     */
    public function __destruct()
    {
        unset($this->service);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	return view('backend.loyaltyrewards.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($club)
    {
    	$clubId = getClubIdBySlug($club);
        $loyaltyRewardsStatus = config('fanslive.PUBLISH_STATUS');

        //Get collection points
        $collectionPoints = $this->collectionPointService->getCollectionPoints($clubId);
    	return view('backend.loyaltyrewards.create', compact('loyaltyRewardsStatus', 'collectionPoints'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $club)
    {
    	$clubId = getClubIdBySlug($club);
        $loyaltyRewards = $this->service->create(
            $clubId,
            auth()->user(),
            $request->all()
        );

        if ($loyaltyRewards) {
            flash('Loyalty reward created successfully')->success();
        } else {
            flash('Loyalty reward could not be created. Please try again.')->error();
        }

        return redirect()->route('backend.loyaltyreward.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $clubId, LoyaltyReward $loyaltyRewards)
    {
        $clubId = getClubIdBySlug($clubId);

        $loyaltyRewardsStatus = config('fanslive.PUBLISH_STATUS');

        //Get collection points
        $collectionPoints = $this->collectionPointService->getCollectionPoints($clubId);

        // Get only loyalty rewards points
        $loyaltyRewardsCollectionPoints = $this->service->loyaltyRewardsCollectionPoints($loyaltyRewards);

        // Get product custom option
        $loyaltyRewardsOptions = $loyaltyRewards->loyaltyRewardsOptions()->get()->toArray();

        return view('backend.loyaltyrewards.edit', compact('loyaltyRewards', 'loyaltyRewardsStatus', 'collectionPoints', 'loyaltyRewardsOptions', 'loyaltyRewardsCollectionPoints'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $clubId, LoyaltyReward $loyaltyRewards)
    {
        $loyaltyRewardsToUpdate = $this->service->update(
            auth()->user(),
            $loyaltyRewards,
            $request->all()
        );

        if ($loyaltyRewardsToUpdate) {
            flash('Loyalty reward updated successfully')->success();
        } else {
            flash('Loyalty reward could not be updated. Please try again.')->error();
        }

        return redirect()->route('backend.loyaltyreward.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($clubId, LoyaltyReward $loyaltyRewards)
    {
        $this->service->deleteLogo($loyaltyRewards);
        if ($loyaltyRewards->delete()) {
            flash('Loyalty reward deleted successfully')->success();
        } else {
            flash('Loyalty reward could not be deleted. Please try again.')->error();
        }

        return redirect()->route('backend.loyaltyreward.index', ['club' => app()->request->route('club')]);
    }

    /**
     * Get Loyalty rewards list data.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLoyaltyRewardsData(Request $request, $club)
    {
        $clubId = getClubIdBySlug($club);
        $loyaltyRewardsList = $this->service->getData(
            $clubId,
            $request->all()
        );

        return $loyaltyRewardsList;
    }
}
