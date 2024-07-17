<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Poll\GetPollDetailsRequest;
use App\Http\Requests\Api\Poll\GetPollsRequest;
use App\Http\Requests\Api\Poll\SavePollResultRequest;
use App\Http\Resources\Poll\Poll as PollResource;
use App\Models\Consumer;
use App\Models\Poll;
use App\Services\PollService;
use JWTAuth;

/**
 * @group Polls
 *
 * APIs for polls.
 */
class PollController extends BaseController
{
    /**
     * Create a poll service variable.
     *
     * @return void
     */
    protected $service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PollService $service)
    {
        $this->service = $service;
    }

    /**
     * Get polls
     * Get all published polls of a club.
     *
     * @bodyParam club_id int required An id of a club. Example: 1
     *
     * @return \Illuminate\Http\Response
     */
    public function getPolls(GetPollsRequest $request)
    {
        $polls = Poll::where('club_id', $request['club_id'])->where('status', 'Published')->where('publication_date', '<', now())->orderByDesc('publication_date')->get();

        return PollResource::collection($polls);
    }

    /**
     * Get poll details
     * Get poll details.
     *
     * @bodyParam id int required An id of a poll. Example: 1
     *
     * @return \Illuminate\Http\Response
     */
    public function getPollDetails(GetPollDetailsRequest $request)
    {
        $poll = Poll::where('id', $request['id'])->first();

        return new PollResource($poll);
    }

    /**
     * Save poll result
     * Save poll result.
     *
     * @bodyParam poll_id int required An id of a poll. Example: 1
     * @bodyParam option_id int required An id of a poll option. Example: 1
     *
     * @return \Illuminate\Http\Response
     */
    public function savePollResult(SavePollResultRequest $request)
    {
        $user = JWTAuth::user();
        $consumerId = Consumer::where('user_id', $user->id)->first()->id;
        $data = $request->all();

        $pollAnswer = $this->service->savePollResult(
            $consumerId,
            $request->all()
        );

        if ($pollAnswer) {
            $poll = Poll::find($data['poll_id']);
            return response()->json([
                'message' => 'Poll result saved successfully.',
                'poll' => new PollResource($poll)
            ]);
        }
    }
}
