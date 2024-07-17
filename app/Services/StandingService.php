<?php

namespace App\Services;

use App\Repositories\StandingRepository;

class StandingService
{
    /**
     * Create a new service instance.
     *
     * @param StandingRepository $repository
     */
    public function __construct(StandingRepository $repository)
    {
        $this->repository = $repository;
    }
}
