<?php

namespace App\Services;

use App\Repositories\StaffRepository;

/**
 * Staff class to handle operator interactions.
 */
class StaffService
{
    /**
     * The staff repository instance.
     *
     * @var StaffRepository
     */
    protected $repository;

    /**
     * Create a new service instance.
     *
     * @param StaffRepository $repository
     */
    public function __construct(StaffRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Handle logic to create a staff user.
     *
     * @param $data
     *
     * @return mixed
     */
    public function create($data)
    {
        $staffUser = $this->repository->create($data);

        return $staffUser;
    }

    /**
     * Handle logic to update a given staff user.
     *
     * @param $data
     * @param $id
     *
     * @return mixed
     */
    public function update($user, $data)
    {
        $staffUser = $this->repository->update($user, $data);

        return $staffUser;
    }

    /**
     * Get staff user data.
     *
     * @param $data
     *
     * @return mixed
     */
    public function getData($data)
    {
        $staffUsers = $this->repository->getData($data);

        return $staffUsers;
    }

    /**
     * Get staff user detail.
     *
     * @param $userId
     *
     * @return mixed
     */
    public function getStaffDetail($userId)
    {
        $staff = $this->repository->getStaffDetail($userId);

        return $staff;
    }

    /**
     * Handle logic to update a given staff.
     *
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function updateStaff($user, $data)
    {
        $staffUser = $this->repository->updateStaff($user, $data);

        return $staffUser;
    }
}
