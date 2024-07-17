<?php

namespace App\Services;

use App\Repositories\UserRoleRepository;

class UserRoleService
{
    /**
     * The user repository instance.
     *
     * @var UserroleRepository
     */
    private $userRoleRepository;

    /**
     * Create a new service instance.
     *
     * @param UserroleRepository $UserroleRepository
     */
    public function __construct(UserRoleRepository $userRoleRepository)
    {
        $this->userRoleRepository = $userRoleRepository;
    }

    /**
     * Handle logic to create a user role user.
     *
     * @param $competition
     * @param $data
     *
     * @return mixed
     */
    public function create($role, $data)
    {
        $userRole = $this->userRoleRepository->create($role, $data);

        return $userRole;
    }

    /**
     * Handle logic to update a given category.
     *
     * @param $data
     * @param $id
     *
     * @return mixed
     */
    public function update($role, $data)
    {
        $userRole = $this->userRoleRepository->update($role, $data);

        return $userRole;
    }

    /**
     * Get User role data.
     *
     * @param $data
     *
     * @return mixed
     */
    public function getData($data)
    {
        $userRole = $this->userRoleRepository->getData($data);

        return $userRole;
    }
}
