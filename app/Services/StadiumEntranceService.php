<?php

namespace App\Services;

use App\Repositories\StadiumEntranceRepository;
use App\Repositories\StadiumGeneralSettingRepository;

/**
 * User class to handle operator interactions.
 */
class StadiumEntranceService
{
    /**
     * Create a new service instance.
     *
     * @param StadiumEntranceRepository $StadiumEntranceRepository
     */
    public function __construct(StadiumEntranceRepository $stadiumEntranceRepository, StadiumGeneralSettingRepository $stadiumGeneralSettingRepository)
    {
        $this->stadiumEntranceRepository = $stadiumEntranceRepository;
        $this->stadiumGeneralSettingRepository = $stadiumGeneralSettingRepository;
    }

    /**
     * Handle logic to update a given category.
     *
     * @param $data
     * @param $id
     *
     * @return mixed
     */
    public function update($user, $clubId, $data)
    {
        $stadiumEntranceToUpdate = $this->stadiumEntranceRepository->update($user, $clubId, $data);

        return $stadiumEntranceToUpdate;
    }

    /**
     * Handle logic to general setting.
     *
     * @param $request
     *
     * @return mixed
     */
    public function updateGenralSettingData($request)
    {
        return $this->stadiumGeneralSettingRepository->updateLatLong($request);
    }

    /**
     * Handle logic to prepare Blocks Data with Stadium entrances.
     *
     * @param $stadiumEntrance
     * @param $stadiumBlocks
     *
     * @return mixed
     */
    public function prepareBlocksData($stadiumEntrance, $stadiumBlocks)
    {
        foreach ($stadiumEntrance as $key=>$stadiumEntranceEach) {
            $blocks = array_column($stadiumEntranceEach->stadiumEntranceBlocks->toArray(), 'stadium_block_id');

            array_walk($blocks, function (&$item) use ($stadiumBlocks) {
                if (isset($stadiumBlocks[$item])) {
                    $item = $stadiumBlocks[$item];
                } else {
                    $item = null;
                }
            });

            $stadiumEntranceEach->blocks = implode(', ', array_filter($blocks));
            $stadiumEntrance[$key] = $stadiumEntranceEach;
        }

        return $stadiumEntrance;
    }

    /**
     * unset class instance or public property.
     */
    public function __destruct()
    {
        unset($this->stadiumEntranceRepository);
        unset($this->stadiumGeneralSettingRepository);
    }
}
