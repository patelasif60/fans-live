<?php

namespace App\Repositories;

use App\Models\BookedEvent;
use App\Models\Event;
use App\Models\EventMembershipPackageAvailability;
use App\Models\MembershipPackage;
use DB;
use Illuminate\Support\Arr;
use App\Models\EventNotification;
use App\Models\EventTransaction;

/**
 * Repository class for model.
 */
class EventRepository extends BaseRepository
{
	/**
	 * Get Match data.
	 *
	 * @param $clubId
	 * @param $data
	 *
	 * @return mixed
	 */
	public function getData($clubId, $data)
	{
		$eventData = DB::table('events')->where('club_id', $clubId);

		if (isset($data['sortby'])) {
			$sortby = $data['sortby'];
			$sorttype = $data['sorttype'];
		} else {
			$sortby = 'events.id';
			$sorttype = 'desc';
		}

		$eventData = $eventData->orderBy($sortby, $sorttype);

		if (isset($data['title']) && trim($data['title']) != '') {
			$eventData->where('events.title', 'like', '%' . $data['title'] . '%');
		}

		if (!empty($data['from_date'])) {
			$eventData->whereDate('events.date_time', '>=', convertDateFormat($data['from_date'], config('fanslive.DATE_CMS_FORMAT.php')));
		}

		if (!empty($data['to_date'])) {
			$eventData->whereDate('events.date_time', '<=', convertDateFormat($data['to_date'], config('fanslive.DATE_CMS_FORMAT.php')));
		}

		$eventListArray = [];
		if (!array_key_exists('pagination', $data)) {
			$eventData = $eventData->paginate($data['pagination_length']);
			$eventListArray = $eventData;
		} else {
			$eventListArray['total'] = $eventData->count();
			$eventListArray['data'] = $eventData->get();
		}

		return $eventListArray;
	}

	/**
	 * Handle logic to create a event.
	 *
	 * @param $clubId
	 * @param $user
	 * @param $data
	 *
	 * @return mixed
	 */
	public function create($clubId, $user, $data)
	{
		DB::beginTransaction();

		try {
			$event = Event::create([
				'club_id' => $clubId,
				'title' => $data['title'],
				'description' => $data['description'],
				'location' => $data['location'],
				'date_time' => convertDateTimezone($data['dateandtime'], $data['global_club_timezone'], null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php')),
				'image' => $data['logo'],
				'image_file_name' => $data['logo_file_name'],
				'rewards_percentage_override' => $data['rewards_percentage_override'],
				'price' => $data['price'],
				'vat_rate' => $data['vat_rate'],
				'number_of_tickets' => $data['number_of_tickets'],
				'status' => $data['status'],
				'created_by' => $user->id,
				'updated_by' => $user->id,
			]);

			if (!empty($data['packageList']) && isset($event->id)) {
				foreach ($data['packageList'] as $key => $val) {
					$dbFields = [
						'event_id' => $event->id,
						'membership_package_id' => $val,
					];
					EventMembershipPackageAvailability::insert($dbFields);
				}
			}
			DB::commit();

			return $event;
		} catch (\Exception $e) {
			DB::rollback();

			return null;
		}
	}

	/**
	 * Handle logic to update a event.
	 *
	 * @param $user
	 * @param $event
	 * @param $data
	 *
	 * @return mixed
	 */
	public function update($user, $event, $data)
	{
		DB::beginTransaction();

		try {
			$event->fill([
				'title' => $data['title'],
				'description' => $data['description'],
				'location' => $data['location'],
				'date_time' => convertDateTimezone($data['dateandtime'], $data['global_club_timezone'], null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php')),
				'image' => $data['logo'],
				'image_file_name' => $data['logo_file_name'],
				'rewards_percentage_override' => $data['rewards_percentage_override'],
				'price' => $data['price'],
				'vat_rate' => $data['vat_rate'],
				'number_of_tickets' => $data['number_of_tickets'],
				'status' => $data['status'],
				'updated_by' => $user->id,
			]);
			$event->save();

			if (!empty($data['packageList']) && isset($event->id)) {
				$eventMappingDelete = EventMembershipPackageAvailability::where('event_id', $event->id)->delete();

				foreach ($data['packageList'] as $key => $val) {
					$dbFields = [
						'event_id' => $event->id,
						'membership_package_id' => $val,
					];
					EventMembershipPackageAvailability::insert($dbFields);
				}
			}
			DB::commit();

			return $event;
		} catch (\Exception $e) {
			DB::rollback();

			return null;
		}
	}

	/**
	 * Handle logic to get only event membership package.
	 *
	 * @param $event
	 *
	 * @return mixed
	 */
	public function getEventMembershipPackage($event)
	{
		$getEventPackage = $event::with(['eventMembershipPackageAvailability'])->where('id', $event->id)->get();

		return $getEventPackage;
	}

	/**
	 * Save event notification.
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function saveEventNotification($data)
	{
		$eventNotification = EventNotification::create([
			'consumer_id' => $data['consumer_id'],
			'club_id' => $data['club_id'],
			'event_id' => $data['event_id'],
			'reason' => $data['reason'],
			'is_notified' => Arr::get($data, 'is_notified', 0),
		]);

		return $eventNotification;
	}

	/**
	 * Handle logic to create a new event.
	 *
	 * @param $data
	 */
	public function createEventPurchase($data)
	{
		$event = EventTransaction::create([
			'event_id' => $data['event_id'],
			'club_id' => $data['club_id'],
			'consumer_id' => $data['consumer_id'],
			'price' => $data['price'],
			'currency' => $data['currency'],
			'per_quantity_price' => $data['per_quantity_price'],
			'vat_rate' => $data['vat_rate'],
			'transaction_reference_id' => $data['transaction_reference_id'],
			'card_details'=>$data['card_details'],
			'payment_status'=>$data['payment_status'],
			'custom_parameters' => $data['custom_parameters'],
		]);

		return $event;
	}


	/**
	 * Handle logic to update event details.
	 *
	 * @param $data
	 * @param $eventTransactionId
	 */
	public function updateEventPurchase($data, $event)
	{
		$event->status = $data['status'];
		$event->psp_reference_id = $data['psp_reference_id'];
		$event->payment_method = $data['payment_method'];
		$event->psp = $data['psp'];
		$event->status_code = $data['status_code'];
		$event->psp_account = $data['psp_account'];
		$event->transaction_timestamp = $data['transaction_timestamp'];
		$event->save();
		return $event;
	}

	/**
	 * Save booked tickets.
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function saveBookedEvents($data)
	{
		$bookedEvents = BookedEvent::create([
			'event_transaction_id' => $data['event_transaction_id'],
			'seat' => $data['seat'],
		]);

		return $bookedEvents;
	}

	/**
     * Get event transactions query.
     *
     * @param $clubId
     * @param $data
     *
     * @return mixed
     */
    public function getEventTransactionQueryForTransactions()
    {
        $eventTransactionQuery = DB::table('event_transactions')->select(
            'event_transactions.id as id',
            'clubs.name as club',
            'clubs.time_zone as club_time_zone',
            'event_transactions.consumer_id as consumer_id',
            'event_transactions.club_id as club_id',
            //'event_transactions.payment_type as payment_type',
            'event_transactions.payment_brand as payment_brand',
            'event_transactions.price as price',
            'event_transactions.fee as fee',
            'event_transactions.currency as currency',
            'event_transactions.status as status',
            'event_transactions.payment_status as payment_status',
            'event_transactions.transaction_timestamp as transaction_timestamp',
            'users.email as email',
            DB::raw('"event" as transaction_type'),
            DB::raw('ROUND(event_transactions.price*(event_transactions.fee/100),2) as fee_amount'),
            DB::raw('CONCAT(users.first_name," ", users.last_name) as name')
        )
        ->leftJoin('consumers', 'consumers.id', '=', 'event_transactions.consumer_id')
        ->leftJoin('users', 'users.id', '=', 'consumers.user_id')
        ->leftJoin('clubs', 'clubs.id', '=', 'event_transactions.club_id')
        ;
        return $eventTransactionQuery;
    }

    /**
     * Get event transactions payment brand.
     *
     * @param $clubId
     * @param $data
     *
     * @return mixed
     */
    public function getPaymentCardType()
    {
        $paymentBrands = DB::table('event_transactions')->select('payment_brand')->groupBy('payment_brand')->get();
        return $paymentBrands;
    }
    public function bookedSeatCount($eventId)
	{
		return EventTransaction::where('event_id',$eventId)->max('to');
	}
	/**
     * Handle logic to get event transaction data.
     * @param transactionReferenceId
     *
     * @return mixed
     */
    public function getEventTransactionData($transactionReferenceId)
    {
        return EventTransaction::where('transaction_reference_id',$transactionReferenceId)->get()->first();
    }
    /**
    * Get all Data
    */
    public function getAllBookedEvent()
    {
    	return BookedEvent::all();
    }
}
