<?php

namespace App\Http\Resources\Consumer;

use App\Http\Resources\ConsumerCard\ConsumerCard as ConsumerCardResource;
use App\Http\Resources\User\User as UserResource;
use App\Http\Resources\Club\Club as ClubResource;
use App\Repositories\LoyaltyRewardPointHistoryRepository;
use App\Services\LoyaltyRewardPointHistoryService;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ConsumerMembershipPackage\ConsumerMembershipPackage as ConsumerMembershipPackageResource;

class Consumer extends JsonResource
{
	/**
	 * A loyalty reward history service.
	 *
	 * @var service
	 */
	protected $loyaltyRewardPointHistoryService;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct($resource)
	{
		parent::__construct($resource);
		$this->loyaltyRewardPointHistoryService = new LoyaltyRewardPointHistoryService(new LoyaltyRewardPointHistoryRepository());
	}

	/**
	 * Destory/Unset object variables.
	 *
	 * @return void
	 */
	public function __destruct()
	{
		unset($this->loyaltyRewardPointHistoryService);
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
		$activeMembershipPackage = $this->getActiveMembershipPackage();
		return [
			'id' => $this->id,
			'user' => new UserResource($this->user),
			'club' => new ClubResource($this->club),
			'date_of_birth' => $this->date_of_birth,
			'receive_offers' => $this->receive_offers,
			'settings' => $this->settings,
			'timezone' => $this->time_zone,
			'loyalty_reward_balance' => $this->loyaltyRewardPointHistoryService->getConsumerLoyaltyRewardPointBalance($this->id),
			'membership_package_detail' => $activeMembershipPackage ? new ConsumerMembershipPackageResource($activeMembershipPackage) : null,
		];
	}
}
