<?php

namespace App\Repositories;

use App\Models\PollOption;

/**
 * Repository class for  model.
 */
class PollOptionRepository extends BaseRepository
{
    public function create($data, $pollId)
    {
        foreach ($data as $val) {
            $pollOption = PollOption::create([
                'poll_id' => $pollId,
                'text'    => $val['answer'],
            ]);
            $pollOption->save();
        }

        return $pollOption;
    }

    public function update($data, $pollId)
    {
        $pollOption = PollOption::where('poll_id', $pollId)->whereNotIn('id', array_filter(array_column($data, 'id')))->delete();
        foreach ($data as $val) {
            $dbFields = [
                'text'    => $val['answer'],
                'poll_id' => $pollId,
            ];
            if ($val['id'] > 0) {
                $pollOption = PollOption::where('id', $val['id'])->update($dbFields);
            } else {
                $pollOption = PollOption::create($dbFields);
                $pollOption->save();
            }
        }

        return $pollOption;
    }

    /**
     * Handle logic to create a poll user.
     *
     * @param $pollOptionId
     *
     * @return mixed
     */
    public function increaseCount($pollOptionId)
    {
        $pollOptions = PollOption::where('id', $pollOptionId)->first();
        $pollOptions->count = $pollOptions->count + 1;
        $pollOptions->save();

        return $pollOptions;
    }
}
