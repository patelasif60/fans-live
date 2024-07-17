<?php

namespace App\Repositories;

use App\Models\BookedTicket;
use App\Models\BookedTicketScanStatus;
use App\Models\MatchTicketNotification;
use App\Models\TicketTransaction;
use App\Models\EventTransaction;
use App\Models\HospitalitySuiteTransaction;
use App\Models\SellMatchTicket;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use DB;

/**
 * Repository class for model.
 */
class TicketRepository extends BaseRepository
{
	/**
	 * Create a new ticket repository instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
	}

	/**
	 * Destroy an instance.
	 *
	 * @return void
	 */
	public function __destruct()
	{
	}

	/**
	 * Save ticket notification.
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function saveTicketNotification($data)
	{
		$ticketNotification = MatchTicketNotification::create([
			'consumer_id'      => $data['consumer_id'],
			'club_id'          => $data['club_id'],
			'match_id'         => $data['match_id'],
			'stadium_block_id' => Arr::get($data, 'stadium_block_id', null),
			'reason'           => $data['reason'],
			'is_notified'      => Arr::get($data, 'is_notified', 0),
		]);

		return $ticketNotification;
	}

	/**
	 * Handle logic to create a new ticket.
	 *
	 * @param $data
	 */
	public function createTicketPurchase($data)
	{
		\Log::info("createticketpurchase" . $data['payment_status']);
		$ticket = TicketTransaction::create([
			'match_id'    => $data['match_id'],
			'club_id'     => $data['club_id'],
			'consumer_id' => $data['consumer_id'],
			'price'       => $data['price'],
			'currency'    => $data['currency'],
			'transaction_reference_id' => $data['transaction_reference_id'],
			'card_details' => $data['card_details'],
			'payment_status' => $data['payment_status'],
			'custom_parameters' => $data['custom_parameters'],
		]);
		return $ticket;
	}

	/**
	 * Handle logic to update ticket details.
	 *
	 * @param $data
	 * @param $ticketTransactionId
	 */
	public function updateTicketPurchase($data, $ticket)
	{
		$ticket->status = $data['status'];
		$ticket->psp_reference_id = $data['psp_reference_id'];
		$ticket->payment_method = $data['payment_method'];
		$ticket->psp = $data['psp'];
		$ticket->status_code = $data['status_code'];
		$ticket->psp_account = $data['psp_account'];
		$ticket->transaction_timestamp = $data['transaction_timestamp'];
		$ticket->save();

		return $ticket;
	}

	/**
	 * Save booked tickets.
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function saveBookedTickets($data)
	{
		$bookedTickets = BookedTicket::create([
			'ticket_transaction_id' => $data['ticket_transaction_id'],
			'stadium_block_seat_id' => $data['stadium_block_seat_id'],
			'seat' 					=> $data['seat'],
			'pricing_band_id'       => $data['pricing_band_id'],
			'price'                 => $data['price'],
			'vat_rate'              => $data['vat_rate'],
		]);

		return $bookedTickets;
	}

	/**
	 * Get ticket transactions query.
	 *
	 * @param $clubId
	 * @param $data
	 *
	 * @return mixed
	 */
	public function getTicketTransactionQueryForTransactions()
	{
		$ticketTransactionQuery = DB::table('ticket_transactions')->select(
			'ticket_transactions.id as id as id',
			'clubs.name as club',
			'clubs.time_zone as club_time_zone',
			'ticket_transactions.consumer_id as consumer_id',
			'ticket_transactions.club_id as club_id',
		   // 'ticket_transactions.payment_type as payment_type',
			'ticket_transactions.payment_brand as payment_brand',
			'ticket_transactions.price as price',
			'ticket_transactions.fee as fee',
			'ticket_transactions.currency as currency',
			'ticket_transactions.status as status',
			'ticket_transactions.payment_status as payment_status',
			'ticket_transactions.transaction_timestamp as transaction_timestamp',
			'users.email as email',
			DB::raw('"ticket" as transaction_type'),
			DB::raw('ROUND(ticket_transactions.price*(ticket_transactions.fee/100),2) as fee_amount'),
			DB::raw('CONCAT(users.first_name," ", users.last_name) as name')
		)
		->leftJoin('consumers', 'consumers.id', '=', 'ticket_transactions.consumer_id')
		->leftJoin('users', 'users.id', '=', 'consumers.user_id')
		->leftJoin('clubs', 'clubs.id', '=', 'ticket_transactions.club_id')
		;
		return $ticketTransactionQuery;
	}

	/**
	 * Get ticket transactions payment brand.
	 *
	 * @param $clubId
	 * @param $data
	 *
	 * @return mixed
	 */
	public function getPaymentCardType()
	{
		$paymentBrands = DB::table('ticket_transactions')->select('payment_brand')->groupBy('payment_brand')->get();
		return $paymentBrands;
	}

	/**
	 * Handle logic to get ticket transaction.
	 *
	 * @param $consumerId
	 *
	 * @return mixed
	 */
	public function getTicketTransaction($consumerId)
	{
		return TicketTransaction::where('consumer_id',$consumerId)->get();
	}

	/**
	 * Handle logic to get event transaction.
	 *
	 * @param $consumerId
	 *
	 * @return mixed
	 */
	public function getEventTransaction($consumerId)
	{
		return EventTransaction::where('consumer_id',$consumerId)->get();
	}

	/**
	 * Handle logic to get hospitality suite transaction.
	 *
	 * @param $consumerId
	 *
	 * @return mixed
	 */
	public function getHospitalitySuiteTransaction($consumerId)
	{
		return HospitalitySuiteTransaction::where('consumer_id',$consumerId)->get();
	}

	/**
	 * Handle logic to create sell match ticket.
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function sellMatchTicket($data)
	{
		$sellMatchTicket = SellMatchTicket::create([
			'booked_ticket_id' => $data['booked_ticket_id'],
			'return_time_to_wallet' => $data['return_time_to_wallet'],
			'account_number' => $data['account_number'],
			'sort_code' => $data['sort_code'],
		]);
		return $sellMatchTicket;
	}

	/**
	 * Handle logic to get sell match ticket.
	 *
	 * @param $matchId
	 * @param $stadiumBlockId
	 *
	 * @return mixed
	 */
	public function getSellMatchTicket($matchId, $stadiumBlockId)
	{
		$sellMatchTickets = SellMatchTicket::leftjoin('booked_tickets', 'booked_tickets.id', '=', 'sell_match_ticket.booked_ticket_id')->leftjoin('ticket_transactions', 'ticket_transactions.id', '=', 'booked_tickets.ticket_transaction_id')->leftjoin('stadium_block_seats', 'stadium_block_seats.id', '=', 'booked_tickets.stadium_block_seat_id')->where('ticket_transactions.match_id','=',$matchId)->where('stadium_block_seats.stadium_block_id','=',$stadiumBlockId)->where('sell_match_ticket.is_active', '=', 1)->where('sell_match_ticket.is_sold', '=', 0)->get();
		return $sellMatchTickets;
	}

	/**
	 * Handle logic to create booked ticket status.
	 *
	 * @param $ticketId
	 * @param $staffId
	 * @param $type
	 *
	 * @return mixed
	 */
	public function createBookedTicketScanStatus($ticketId, $staffId, $type)
	{
		$bookedTicketScanStatus = BookedTicketScanStatus::create([
			'staff_id' => $staffId,
			'ticket_id' => $ticketId,
			'scan_datetime' => Carbon::now()->format('Y-m-d H:i:s'),
			'type' => $type,
		]);
		return $bookedTicketScanStatus;
	}
	/**
     * Handle logic to get ticket transaction data.
     * @param transactionReferenceId
     *
     * @return mixed
     */
    public function getTicketTransactionData($transactionReferenceId)
    {
        return TicketTransaction::where('transaction_reference_id',$transactionReferenceId)->get()->first();
    }
    public function getAllBookedMatchTicket()
    {
    	return BookedTicket::all();
    }

}
