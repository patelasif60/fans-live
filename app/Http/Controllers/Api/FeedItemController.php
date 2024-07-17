<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookedTicket\BookedTicket as BookedTicketResource;
use App\Http\Resources\BookedTicket\BookedTicketCollection;
use App\Http\Requests\Api\FeedItem\GetRSSDetailsRequest;
use App\Http\Requests\Api\FeedItem\GetUpdateFeedsRequest;
use App\Http\Resources\Match\MatchBrief as MatchBriefResource;
use App\Http\Resources\FeedItem\FeedItem as FeedItemResource;
use App\Http\Resources\News\News as NewsResource;
use App\Http\Resources\Poll\Poll as PollResource;
use App\Http\Resources\CTA\CTA as CTAResource;
use App\Models\FeedItem;
use App\Models\News;
use App\Models\Poll;
use App\Models\Consumer;
use App\Models\TicketTransaction;
use App\Models\SellMatchTicket;
use App\Models\BookedTicket;
use App\Models\CTA;
use App\Models\Match;
use JWTAuth;
use Carbon\Carbon;
use DB;

/**
 * @group Update feeds
 *
 * APIs for feeds.
 */
class FeedItemController extends Controller
{
	/**
	 * Get update feeds
	 * Get all published update feeds of a club.
	 *
	 * @bodyParam club_id int required An id of a club. Example: 1
	 * @bodyParam page int required Page number. Example: 1
	 * @bodyParam per_page int required Number of records per page. Example: 5
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getUpdateFeeds(GetUpdateFeedsRequest $request)
	{
		$response = [];
		$user = JWTAuth::user();
		$consumer = Consumer::where('user_id', $user->id)->first();
		$ticketTransactions = [];
		$match = Match::where('status', 'scheduled')->where(DB::raw('CONVERT(kickoff_time, date)'), Carbon::today())
					->where(function($query) use($consumer) {
						$query->where('home_team_id', $consumer->club_id)
							->orWhere('away_team_id', $consumer->club_id);
					})
					->orderBy('kickoff_time', 'asc')->first();

		if($match) {
			$ticketTransactions = TicketTransaction::where('match_id', $match->id)
				->where('club_id', $consumer->club_id)
				->where('consumer_id', $consumer->id)
				->get();
		}
		if (count($ticketTransactions) > 0) {
			// $match = $ticketTransactions->match;
			$response['to_show_view_ticket_display_screen'] = true;
			$response['match_detail'] = new MatchBriefResource($match);
			$response['match_half_time'] = $match ? Carbon::parse($match->kickoff_time)->addMinutes(45)->format("Y-m-d H:i:s") : null;
			// $response['booked_tickets_detail'] = BookedTicketResource::collection($ticketTransactions->bookedTickets);
			$resaleTicketId=$this->resaleIds();
            $bookedTickets = $this->getBookedTickets($ticketTransactions->pluck('id'), $resaleTicketId);

			$response['booked_tickets_detail'] = BookedTicketCollection::make($bookedTickets)->checkWalletDetailsFlag(false);
			$ctas = CTA::where('club_id', $consumer->club_id)->where('status', 'Published')->where('publication_date', '<', now())->get();
			$response['ctas'] = CTAResource::collection($ctas);

		} else {
			$response['to_show_view_ticket_display_screen'] = false;

			$news = News::where('club_id', $consumer->club->id)->where('status', 'Published')->where('publication_date', '<', now())->get();
			$polls = Poll::where('club_id', $consumer->club->id)->where('status', 'Published')->where('publication_date', '<', now())->get();
			$feedItem = FeedItem::where('club_id', $consumer->club->id)->where('status', 'Published')->where('publication_date', '<', now())->get();

			$newsData = NewsResource::collection($news);
			$pollsData = PollResource::collection($polls);
			$feedItemData = FeedItemResource::collection($feedItem);

			$collection = collect([]);

			$dataCollection = $collection->merge($feedItemData)->merge($newsData)->merge($pollsData);

			$sorted = $dataCollection->sortByDesc(function ($obj, $key) {
				return Carbon::parse($obj['publication_date'])->unix();
			});

			$data = $sorted->values()->forPage($request['page'], $request['per_page'])->toArray();

			$ticketTransaction = TicketTransaction::join('matches', 'match_id', '=', 'matches.id')
				->where('club_id', $consumer->club_id)
				->where('consumer_id', $consumer->id)
				->where('matches.status', 'scheduled')
				->where(DB::raw('CONVERT(kickoff_time, date)'), '>', Carbon::today())
				->orderBy('kickoff_time', 'asc')
				->first();

			if (isset($ticketTransaction)) {
				$response['to_show_match_sticky_header'] = true;
				$match = $ticketTransaction->match;
				$matchData['match_id'] = $match->id;
				$matchData['kickoff'] = $match->kickoff_time;
				$matchData['home_team_id'] = $match->home_team_id;
				$matchData['home_team_name'] = $match->homeTeam->name;
				$matchData['home_team_logo'] = $match->homeTeam->logo;
				$matchData['away_team_id'] = $match->away_team_id;
				$matchData['away_team_name'] = $match->awayTeam->name;
				$matchData['away_team_logo'] = $match->awayTeam->logo;
				$response['ticket_purchased_of_next_match'] = $matchData;
			} else {
				$response['to_show_match_sticky_header'] = false;
				$response['ticket_purchased_of_next_match'] = null;
			}

			$response['feed_items'] = array_values($data);
		}

		return response()->json([
			'data' => $response,
		]);
	}

	/**
	 * Get RSS details
	 * Get RSS details.
	 *
	 * @bodyParam id int required An id of a RSS feed. Example: 1
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getRSSDetails(GetRSSDetailsRequest $request)
	{
		$feed = FeedItem::find($request['id']);

		return new FeedItemResource($feed);
	}
	 /**
     * Get user resale booked ticket ID.
     * Get user resale booked ticket ID.
     *
     * @return \Illuminate\Http\Response
     */
    public function resaleIds()
    {
        return SellMatchTicket::orWhere(function ($q) {
                      $q->where('is_sold',0)->where('is_active',1);
            })
            ->orWhere(function ($q) {
                      $q->where('is_sold',1)->where('is_active',0);
            })->pluck('booked_ticket_id')->toArray();
    }
     /**
     * Get available tickets.
     */
    public function getBookedTickets($ticketTransactionIds, $resaleTicketIds)
    {
        if($resaleTicketIds)
        {
            return BookedTicket::whereIn('ticket_transaction_id', $ticketTransactionIds)->whereNotIn('id', $resaleTicketIds)->get();
        }
        return BookedTicket::whereIn('ticket_transaction_id', $ticketTransactionIds)->get();
    }
}
