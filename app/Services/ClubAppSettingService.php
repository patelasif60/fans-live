<?php
namespace App\Services;

use App\Repositories\ClubAppSettingRepository;

/**
 * User class to handle operator interactions.
 */
class ClubAppSettingService
{
    /**
     * The user repository instance.
     *
     * @var clubAppSettingRepository
     */
    private $clubAppSettingRepository;

    /**
     * Create a new service instance.
     *
     * @param ClubAppSettingRepository $clubAppSettingRepository
     */
    public function __construct(ClubAppSettingRepository $clubAppSettingRepository)
    {
        $this->clubAppSettingRepository = $clubAppSettingRepository;
    }

    /**
     * Handle logic to update club app setting.
     *
     * @param $user
     * @param $clubId
     * @param $data
     *
     * @return boolean
     */
    public function update($user, $clubId, $data, $modules)
    {
        //Save Club Module Settings
        $clubModuleSettings = $this->clubAppSettingRepository->getClubModuleSettings($clubId);
        if ($clubModuleSettings->count() > 0) {
            if (!isset($data['modules']) || empty($data['modules'])) {
                $this->clubAppSettingRepository->updateModuleEntry($clubModuleSettings, 0, $user);
            } else {
                $moduleIds = $clubModuleSettings->pluck('module_id')->toArray();
                $clubModulesDiff = array_diff($moduleIds, $data['modules']);
                if (empty($clubModulesDiff)) {
                    $this->clubAppSettingRepository->updateModuleEntry($clubModuleSettings, 1, $user);
                }
                $clubModuleSettings = $clubModuleSettings->get()->keyBy('module_id');
                foreach($clubModuleSettings as $key => $clubModuleSetting) {
                    $this->clubAppSettingRepository->updateModuleEntry($clubModuleSetting, in_array($key, $data['modules']) ? 1 : 0, $user);
                }
            }
        } else {
            foreach($modules as $moduleId) {
                $isActive = !empty($data['modules']) && in_array($moduleId, $data['modules']) ? 1 : 0;
                $this->clubAppSettingRepository->createModuleEntry($user, $clubId, $moduleId, $isActive);
            }
        }

        //Save Club Loyalty Points Settings
        $clubLoyaltyPointSetting = $this->clubAppSettingRepository->getClubLoyaltyPointSetting($clubId);
        if(isset($clubLoyaltyPointSetting)) {
            $this->clubAppSettingRepository->updateClubLoyaltyPointSetting($user, $clubLoyaltyPointSetting, $data);
        } else {
            $this->clubAppSettingRepository->createClubLoyaltyPointSettingEntry($user, $clubId, $data);
        }

        //Save Club Text Settings
        $clubTextSetting = $this->clubAppSettingRepository->getClubTextSetting($clubId);
        if(isset($clubTextSetting)) {
            $this->clubAppSettingRepository->updateClubTextSetting($user, $clubTextSetting, $data);
        } else {
            $this->clubAppSettingRepository->createClubTextSettingEntry($user, $clubId, $data);
        }

        //Save Club Opening Time Settings
        $clubOpeningTimeSetting = $this->clubAppSettingRepository->getClubOpeningTimeSetting($clubId);
        if(isset($clubOpeningTimeSetting)) {
            $this->clubAppSettingRepository->updateClubOpeningTimeSetting($user, $clubOpeningTimeSetting, $data);
        } else {
            $this->clubAppSettingRepository->createClubOpeningTimeSettingEntry($user, $clubId, $data);
        }

        return true;
    }

    /**
     * Get all modules.
     *
     */
    public function getAllModules()
    {
        return $this->clubAppSettingRepository->getAllModules();
    }

    /**
     * Get club opening times.
     *
     * @param $clubId
     *
     * @return mixed
     */
    public function getClubOpeningTimeSetting($clubId)
    {
        return $this->clubAppSettingRepository->getClubOpeningTimeSetting($clubId);
    }

    /**
     * Get club opening times.
     *
     * @param $clubId
     *
     * @return mixed
     */
    public function getClubLoyaltyPointSetting($clubId)
    {
        return $this->clubAppSettingRepository->getClubLoyaltyPointSetting($clubId);
    }
}