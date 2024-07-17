<?php

namespace App\Repositories;

use App\Models\PollAnswer;

/**
 * Repository class for model.
 */
class PollAnswerRepository extends BaseRepository
{
    /**
     * Handle logic to create a poll user.
     *
     * @param $consumerId
     * @param $data
     *
     * @return mixed
     */
    public function savePollResult($consumerId, $data)
    {
        $pollAnswer = PollAnswer::create([
            'poll_id'        => $data['poll_id'],
            'consumer_id'    => $consumerId,
            'poll_option_id' => $data['option_id'],
        ]);

        return $pollAnswer;
    }
}
