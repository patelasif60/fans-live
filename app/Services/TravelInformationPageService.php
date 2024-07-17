<?php

namespace App\Services;

use App\Repositories\TravelInformationPageRepository;
use Storage;

/**
 * User class to handle operator interactions.
 */
class TravelInformationPageService
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
    private $travelInformationPageRepository;

    /**
     * Create a new service instance.
     *
     * @param CompetitionRepository $CompetitionRepository
     */
    public function __construct(TravelInformationPageRepository $travelInformationPageRepository)
    {
        $this->logoPath = config('fanslive.IMAGEPATH.travel_information_photo');
        $this->iconPath = config('fanslive.IMAGEPATH.travel_information_icon');
        $this->travelInformationPageRepository = $travelInformationPageRepository;
    }

    /**
     * Handle logic to create a travel information user.
     *
     * @param $clubId
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($clubId, $user, $data)
    {
        if (isset($data['logo'])) {
            $logoFileName = pathinfo($data['logo']->getClientOriginalName(), PATHINFO_FILENAME);
            $logoFileExtension = pathinfo($data['logo']->getClientOriginalName(), PATHINFO_EXTENSION);
            $logoFile = $logoFileName.'_'.now()->timestamp.'.'.$logoFileExtension;
            $s3path = $this->logoPath.$logoFile;
            $disk = Storage::disk('s3');
            $disk->put($s3path, file_get_contents($data['logo']), 'public');
            $logoUrl = $disk->url($s3path);
        } else {
            $logoUrl = null;
            $logoFile = null;
        }
        $data['logo'] = $logoUrl;
        $data['logo_file_name'] = $logoFile;

        $icon = uploadImageToS3($data['icon'], $this->iconPath);
        $data['icon'] = $icon['url'];
        $data['icon_file_name'] = $icon['file_name'];
        $travelInformationPage = $this->travelInformationPageRepository->create($clubId, $user, $data);

        return $travelInformationPage;
    }

    /**
     * Handle logic to update a given category.
     *
     * @param $user
     * @param $travelInformationPage
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $travelInformationPage, $data)
    {
        $disk = Storage::disk('s3');
        if (isset($data['logo'])) {
            $existingLogo = $this->logoPath.$travelInformationPage->photo_file_name;
            $disk->delete($existingLogo);

            $logo = uploadImageToS3($data['logo'], $this->logoPath);
            $data['logo'] = $logo['url'];
            $data['logo_file_name'] = $logo['file_name'];
        } else {
            $data['logo'] = null;
            $data['logo_file_name'] = null;
            if ($data['logo_edit']) {
                $data['logo'] = $travelInformationPage->photo;
                $data['logo_file_name'] = $travelInformationPage->photo_file_name;
            }
        }

        if (isset($data['icon'])) {
            $existingLogo = $this->iconPath.$travelInformationPage->icon_file_name;
            $disk->delete($existingLogo);

            $icon = uploadImageToS3($data['icon'], $this->iconPath);
            $data['icon'] = $icon['url'];
            $data['icon_file_name'] = $icon['file_name'];
        } else {
            $data['icon'] = $travelInformationPage->icon;
            $data['icon_file_name'] = $travelInformationPage->icon_file_name;
        }

        $travelInformationUpdate = $this->travelInformationPageRepository->update($user, $travelInformationPage, $data);

        return $travelInformationUpdate;
    }

    public function deleteLogo($travelInformationPage)
    {
        $disk = Storage::disk('s3');
        $logo = $this->logoPath.$travelInformationPage->image_file_name;

        return $disk->delete($logo);
    }

    /**
     * Get Travel information data.
     *
     * @param $clubId
     * @param $data
     *
     * @return mixed
     */
    public function getTravelInformationPageData($clubId, $data)
    {
        $travelInformationPage = $this->travelInformationPageRepository->getTravelInformationPageData($clubId, $data);

        return $travelInformationPage;
    }
}
