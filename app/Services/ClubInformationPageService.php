<?php
namespace App\Services;

use File;
use Storage;
use App\Repositories\ClubInformationPageRepository;

/**
 * User class to handle operator interactions.
 */
class ClubInformationPageService
{
    /**
    * @var predefined logo path
    */
    protected $logoPath;
	/**
    * The user repository instance.
    *
    * @var CompetitionRepository
    */
    private $clubInformationPageRepository;

	/**
    * Create a new service instance.
    *
    * @param CompetitionRepository $CompetitionRepository
    */
    public function __construct(ClubInformationPageRepository $clubInformationPageRepository)
    {
    	$this->iconPath = config('fanslive.IMAGEPATH.club_information_icon');
        $this->clubInformationPageRepository = $clubInformationPageRepository;
    }

    /**
    * Handle logic to create a club information user.
    *
    * @param $clubId
    * @param $user
    * @param $data
    * @return mixed
    */
    public function create($clubId, $user, $data)
    {
        if(isset($data['icon'])) {
            $icon = uploadImageToS3($data['icon'], $this->iconPath);
            $data['icon'] = $icon['url'];
            $data['icon_file_name'] = $icon['file_name'];
        }
        $clubInformationPage = $this->clubInformationPageRepository->create($clubId, $user, $data);
        return $clubInformationPage;  
    }


    /**
    * Handle logic to update a given category.
    *
    * @param $user
    * @param $clubInformationPage
    * @param $data
    * @return mixed
    */
    public function update($user, $clubInformationPage, $data)
    {
        $disk = Storage::disk('s3');
        if (isset($data['icon'])) {            
            $existingIcon = $this->iconPath.$clubInformationPage->icon_file_name;
            $disk->delete($existingIcon);
            $icon = uploadImageToS3($data['icon'], $this->iconPath);
            $data['icon'] = $icon['url'];
            $data['icon_file_name'] = $icon['file_name'];
        } else {
            $data['icon'] =  $clubInformationPage->icon;
            $data['icon_file_name'] = $clubInformationPage->icon_file_name;
        }
        
        $clubInformationUpdate = $this->clubInformationPageRepository->update($user, $clubInformationPage, $data);
        return $clubInformationUpdate;
    }

    public function deleteIcon($clubInformationPage)
    {
        $disk = Storage::disk('s3');
        $icon = $this->iconPath.$clubInformationPage->icon_file_name;
        return $disk->delete($icon);
    }

    /**
    * Get Club information data
    *
    * @param $clubId
    * @param $data
    * @return mixed
    */
    public function getClubInformationPageData($clubId, $data)
    {
        $clubInformationPage = $this->clubInformationPageRepository->getClubInformationPageData($clubId, $data);
        return $clubInformationPage;
    }

}