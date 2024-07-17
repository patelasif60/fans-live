<?php

namespace App\Services;

use App\Mail\CreateConsumerPassword;
use App\Models\Consumer;
use App\Models\MatchTicketNotification;
use App\Models\Match;
use App\Models\SellMatchTicket;
use App\Models\MembershipPackage;
use App\Repositories\ConsumerRepository;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

/**
 * Consumer class to handle operator interactions.
 */
class ConsumerService
{
	/**
	 * The consumer repository instance.
	 *
	 * @var repository
	 */
	protected $repository;

	/**
	 * Create a new service instance.
	 *
	 * @param ConsumerRepository $repository
	 */
	public function __construct(ConsumerRepository $repository)
	{
		$this->repository = $repository;
	}

	/**
	 * Handle logic to create a consumer user.
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function create($data)
	{
		$consumerUser = $this->repository->create($data);

		//Send email
		$consumerUser = $this->createVerifyConsumerToken($consumerUser);
		Mail::to($consumerUser)->send(new CreateConsumerPassword($consumerUser));

		return $consumerUser;

	}

	/**
	 * Handle logic to create a cms user token.
	 *
	 * @param $user
	 *
	 * @return mixed
	 */
	public function createVerifyConsumerToken($user)
	{
		if ($user->verifyUser) {
			$user->verifyUser->delete();
		}
		$user->verifyUser()->create([
			'token' => Str::random(40),
		]);

		return $user->fresh('verifyUser');
	}


	/**
	 * Handle logic to update a given consumer user.
	 *
	 * @param $user
	 * @param $data
	 *
	 * @return mixed
	 */
	public function update($user, $data)
	{
		$consumerUser = $this->repository->update($user, $data);

		return $consumerUser;
	}

	/**
	 * Get consumer user data.
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function getData($data)
	{
		$consumerUsers = $this->repository->getData($data);

		return $consumerUsers;
	}

	/**
	 * Get consumer user detail.
	 *
	 * @param $userId
	 *
	 * @return mixed
	 */
	public function getConsumerDetail($userId)
	{
		$consumer = $this->repository->getConsumerDetail($userId);

		return $consumer;
	}

	/**
	 * Get consumer user detail with club.
	 *
	 * @param $userId
	 *
	 * @return mixed
	 */
	public function getConsumerDetailWithClub($userId)
	{
		$consumer = $this->repository->getConsumerDetailWithClub($userId);

		return $consumer;
	}


	/**
	 * Handle logic to update a given consumer.
	 *
	 * @param $user
	 * @param $data
	 *
	 * @return mixed
	 */
	public function updateConsumer($user, $data)
	{
		$consumerUser = $this->repository->updateConsumer($user, $data);

		return $consumerUser;
	}

	/**
	 * Handle logic to delete a given consumer.
	 *
	 * @param $userId
	 *
	 * @return mixed
	 */
	public function deleteConsumer($userId)
	{
		$consumer = $this->repository->deleteConsumer($userId);

		return $consumer;
	}

	/**
	 * Handle logic to update a consumer settings.
	 *
	 * @param $user
	 * @param $data
	 *
	 * @return mixed
	 */
	public function updateSettings($user, $data)
	{
		$consumer = $this->repository->updateSettings($user, $data);

		return $consumer;
	}

	/**
	 * Handle logic to check for consumer membership package and also check for ticket sold out for a match or for * a block.
	 *
	 * @param $consumer
	 * @param $membershipPackages
	 *
	 * @return mixed
	 */
	public function checkForMembershipPackageForMatch($consumer, $membershipPackages)
	{
		$isTicketAvailable = true;
		$ticketAvailabilityMessage = 'Tickets for this match are available.';
		$ticketAvailabilityButtonText = 'Select seats';
		$ticketUnavailibilityReason = null;

		$allFansliveMembershipPackageId = config('fanslive.ALL_FANS_MEMBERSHIP_PACKAGE_ID');
		$membershipPackageIds = $membershipPackages->pluck('membership_package_id')->toArray();

		if(!in_array($allFansliveMembershipPackageId, $membershipPackageIds)) {
			$consumerActiveMembershipPackage = $consumer->getActiveMembershipPackage();
			$consumerMembershipPackage = $consumerActiveMembershipPackage ? $consumerActiveMembershipPackage->membershipPackage : MembershipPackage::find($allFansliveMembershipPackageId);
			$matchTicketingMembershipPackage = $membershipPackages->where('membership_package_id', $consumerMembershipPackage->id)->first();

			if ($matchTicketingMembershipPackage) {
				if (Carbon::parse($matchTicketingMembershipPackage->date) > Carbon::now()) {
					$isTicketAvailable = false;
				}
			} else {
				$isTicketAvailable = false;
			}
			if(!$isTicketAvailable) {
				$ticketUnavailibilityReason = 'unavailable';
				$ticketAvailabilityMessage = 'Tickets are currently not available for '.$consumerMembershipPackage->title.' members. Tap the button below to be notified if tickets become available.';
				$ticketAvailabilityButtonText = 'Tickets unavailable - notify me';
			}
		}

		return [
			'is_ticket_available'             => $isTicketAvailable,
			'ticket_unavailibility_reason'    => $ticketUnavailibilityReason,
			'ticket_availability_message'     => $ticketAvailabilityMessage,
			'ticket_availability_button_text' => $ticketAvailabilityButtonText,
		];
	}

	/**
	 * Handle logic to check whether consumer has already sent a notify request.
	 *
	 * @param $matchId
	 * @param $consumer
	 *
	 * @return mixed
	 */
	public function isAlreadyNotifiedForMatch($matchId, $consumer)
	{
		$isMatchButtonDisabled = false;
		$matchTicketNotifications = MatchTicketNotification::where('match_id', $matchId)->where('consumer_id', $consumer->id)->where('is_notified', true)->get();
		foreach ($matchTicketNotifications as $matchTicketNotification) {
			if (($matchTicketNotification->reason === 'sold_out' && $matchTicketNotification->stadium_block_id === null) || $matchTicketNotification->reason === 'unavailable') {
				$isMatchButtonDisabled = true;
				break;
			}
		}

		return $isMatchButtonDisabled;
	}
	/**
	* Available ticket
	*/
	public function getBookedicket($matchId)
	{
		$match = Match::find($matchId);
		$bookedTicketIds = $match->getMatchTickets($match, $match->id)->get()->pluck('id')->toArray();
		$sellTicket = SellMatchTicket::whereIn('booked_ticket_id', $bookedTicketIds)->where('is_sold', 0)->where('is_active', 1)->count();
		return count($bookedTicketIds) - $sellTicket;
	}

}
