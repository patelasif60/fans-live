<?php

namespace App\Http\Resources\Poll;

use Carbon\Carbon;
use App\Models\Consumer;
use App\Http\Resources\PollOption\PollOptionCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class Poll extends JsonResource
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct($resource, $consumer = NULL, $accessFrom = NULL)
    {
        parent::__construct($resource);
        $this->consumer = $consumer;
        $this->accessFrom = $accessFrom;
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
        $consumer = null;
        $user = \JWTAuth::user();
        if($user) {
            $consumer = Consumer::where('user_id', $user->id)->first();
        }
        $timeAgo = null;

        if($consumer) {
            $date = convertDateTimezone($this->getRawOriginal('publication_date'), 'UTC', $consumer->time_zone);
            $timeAgo = getDateDiff($date, $consumer->time_zone);
        }

        return [
            'id'                    => $this->id,
            'title'                 => $this->title,
            'type'                  => 'poll',
            'question'              => $this->question,
            'answers'               => PollOptionCollection::make($this->pollOptions)->totalOptionsCount($this->option_count),
            'time_ago'              => $timeAgo,
            'publication_date'      => $this->publication_date,
            'closing_date'          => $this->closing_date,
            'display_results_date'  => $this->display_results_date,
            'is_poll_started'       => Carbon::parse($this->publication_date)->lte(Carbon::now()),
            'is_poll_ended'         => $this->closing_date ? Carbon::parse($this->closing_date)->lte(Carbon::now()) : false,
            'to_show_result'        => $this->display_results_date ? Carbon::parse($this->display_results_date)->lte(Carbon::now()) : false,
            'associated_match'      => $this->associated_match,
            'is_user_responded'     => $consumer ? isUserResponded($this->id) : null,
        ];
    }
}
