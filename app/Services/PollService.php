<?php

namespace App\Services;

use App\Repositories\PollAnswerRepository;
use App\Repositories\PollOptionRepository;
use App\Repositories\PollRepository;

class PollService
{
    /**
     * The user repository instance.
     *
     * @var pollRepository
     */
    private $pollRepository;

    /**
     * A poll answer repository instance.
     *
     * @var pollAnswerRepository
     */
    private $pollAnswerRepository;

    /**
     * Create a new service instance.
     *
     * @param PollRepository $pollRepository
     */
    public function __construct(PollRepository $pollRepository, PollOptionRepository $pollOptionRepository, PollAnswerRepository $pollAnswerRepository)
    {
        $this->pollRepository = $pollRepository;
        $this->pollOptionRepository = $pollOptionRepository;
        $this->pollAnswerRepository = $pollAnswerRepository;
    }

    /**
     * Handle logic to get poll options.
     *
     * @param $pollId
     *
     * @return mixed
     */
    public function getPollOptions($pollId)
    {
        return $this->pollRepository->getPollOptions($pollId);
    }

    /**
     * Handle logic to create a poll.
     *
     * @param $clubId
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($clubId, $user, $data)
    {
        $poll = $this->pollRepository->create($clubId, $user, $data);
        $this->pollOptionRepository->create($data['answers'], $poll->id);

        return $poll;
    }

    /**
     * Handle logic to update a given poll.
     *
     * @param $user
     * @param $poll
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $poll, $data)
    {
        $pollUpdate = $this->pollRepository->update($user, $poll, $data);
        $this->pollOptionRepository->update($data['answers'], $poll->id);

        return $pollUpdate;
    }

    /**
     * Get Poll data.
     *
     * @param $clubId
     * @param $data
     *
     * @return mixed
     */
    public function getData($clubId, $data)
    {
        $poll = $this->pollRepository->getData($clubId, $data);

        return $poll;
    }

    /**
     * Save poll result.
     *
     * @param $consumerId
     * @param $data
     *
     * @return mixed
     */
    public function savePollResult($consumerId, $data)
    {
        $poll = $this->pollAnswerRepository->savePollResult($consumerId, $data);
        $pollOption = $this->pollOptionRepository->increaseCount($data['option_id']);

        return $poll;
    }
}
