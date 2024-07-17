<?php

namespace App\Services;

use App\Repositories\TravelWarningRepository;

/**
 * Travel warning class to handle operator interactions.
 */
class TravelWarningService
{
    /**
     * The Travel warning repository instance.
     *
     * @var repository
     */
    protected $repository;

    /**
     * Create a new service instance.
     *
     * @param TravelWarningRepository $repository
     */
    public function __construct(TravelWarningRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get travel warnings data.
     *
     * @param $clubId
     * @param $data
     *
     * @return mixed
     */
    public function getData($clubId, $data)
    {
        $travelwarnings = $this->repository->getData($clubId, $data);

        return $travelwarnings;
    }

    /**
     * Handle logic to create a Travel Warnings.
     *
     * @param $clubId
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($clubId, $user, $data)
    {
        $travelwarnings = $this->repository->create($clubId, $user, $data);

        return $travelwarnings;
    }

    /**
     * Handle logic to update a given Travel Warnings.
     *
     * @param $data
     * @param $id
     *
     * @return mixed
     */
    public function update($user, $travelwarnings, $data)
    {
        $travelwarningsToUpdate = $this->repository->update($user, $travelwarnings, $data);

        return $travelwarningsToUpdate;
    }

    /**
     * Handle logic to get Travel Warnings.
     *
     * @param $userId
     *
     * @return mixed
     */
    public function getTravelWarnings($userId)
    {
        return $this->repository->getTravelWarnings($userId);
    }
}
