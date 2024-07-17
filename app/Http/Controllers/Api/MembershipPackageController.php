<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\MembershipPackage\GetRequest;
use App\Http\Requests\Api\MembershipPackage\MembershipPackagePaymentRequest;
use App\Http\Requests\Api\MembershipPackage\MakePaymentRequest;
use App\Http\Requests\Api\MembershipPackage\ValidatePaymentRequest;
use App\Http\Resources\ConsumerMembershipPackage\ConsumerMembershipPackage as ConsumerMembershipPackageResource;
use App\Http\Resources\MembershipPackage\MembershipPackage as MembershipPackageResource;
use App\Models\ClubLoyaltyPointSetting;
use App\Models\Consumer;
use App\Models\MembershipPackage;
use App\Services\LoyaltyRewardPointHistoryService;
use App\Services\MembershipPackageService;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use JWTAuth;
use App\Jobs\SendMembershipTransactionEmail;
use App\Services\ClubService;
use App\Services\UserService;
//use App\Models\ConsumerMembershipPackage;
/**
 * @group Membership package
 *
 * APIs for Membership package.
 */
class MembershipPackageController extends BaseController
{
    /**
     * Create a membership package variable.
     *
     * @return void
     */
    protected $service;
	protected $loyaltyRewardPointHistoryService;
    protected $userService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(MembershipPackageService $service, LoyaltyRewardPointHistoryService $loyaltyRewardPointHistoryService,UserService $userService)
    {
        $this->service = $service;
		$this->loyaltyRewardPointHistoryService = $loyaltyRewardPointHistoryService;
        $this->userService = $userService;
    }

    /**
     * Destory/Unset object variables.
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->service);
    }

    /**
     * Get membership packages
     * Get membership packages.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMembershipPackages(GetRequest $request)
    {
        $user = JWTAuth::user();
        $consumer = Consumer::where('user_id', $user->id)->first();
        $membershipPackages = MembershipPackage::where('status', 'Published')->where('club_id', $consumer->club_id)->get();

        return MembershipPackageResource::collection($membershipPackages);
    }
    /**
     * Make paymet for membership package
     * Make paymet for membership package
     *
    * @bodyParam membership_package_id int required The id of membership package. Example: 1
    * @return \Illuminate\Http\Response
    */
    public function makeMembershipPackageayment(MakePaymentRequest $request)
    {
        $data = $request->all();
        $user = JWTAuth::user();
        $consumer = Consumer::where('user_id', $user->id)->first();
        $membershipPackagesRewardPercentage = null;

        $membershipPackage = $this->service->membershipPackageObj($data['membership_package_id']);
        $consumerMembershipPackage = $this->service->updateConsumerMembershipPackagePurchase($data,$membershipPackage,$consumer);

        if($data['transaction_summary']['data']['status']=='failed')
        {
            return $consumerMembershipPackage;
        }

        $this->service->updateReceiptNumberOfConsumerMembershipPackage($consumer, $consumerMembershipPackage);

        if ($consumerMembershipPackage->status === 'successful') {
            $this->service->deactiveAllConsumersMembershipPackages($consumer->club_id, $consumer->id);
            $this->service->activateConsumerMembershipPackage($consumerMembershipPackage->id);
        }
        // Loyalty point calculation
        $membershipPackagesRewardPrice = $consumerMembershipPackage->price;

		if($membershipPackage->rewards_percentage_override !== null && $membershipPackage->rewards_percentage_override !== '') {
	        $membershipPackagesRewardPercentage = $membershipPackage->rewards_percentage_override;
	    } else {
	    	$clubLoyaltyPointData = ClubLoyaltyPointSetting::where('club_id', $consumer->club_id)->first();
	        $membershipPackagesRewardPercentage = $clubLoyaltyPointData->membership_packages_reward_percentage;
	    }
        $earnedLoyaltyPoints = $membershipPackagesRewardPrice * $membershipPackagesRewardPercentage;

        $loyaltyRewardPointHistory = $this->loyaltyRewardPointHistoryService->createLoyaltyRewardPointHistory($consumer, $consumerMembershipPackage->id, $earnedLoyaltyPoints, 'membership');

        //$consumerMembershipPackage = ConsumerMembershipPackage::find(1);
        $clubAdmins = $this->userService->clubAdmin($consumer->club_id);
        $superAdmins = $this->userService->superAdmin();
        SendMembershipTransactionEmail::dispatch($consumerMembershipPackage,$consumer,$clubAdmins,$superAdmins)->onQueue(config('fanslive.TRANSACTION_EMAILS'));
        return new ConsumerMembershipPackageResource($consumerMembershipPackage);
    }

    /**
     * Validate membership package Payment
     */
    public function validateMembershipPackagePayment(ValidatePaymentRequest $request)
    {
    	return $this->service->validateMembershipPackagePayment($request->all());
    }
}
