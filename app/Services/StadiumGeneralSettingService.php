<?php

namespace App\Services;

use App\Models\StadiumGeneralSetting;
use App\Repositories\StadiumGeneralSettingRepository;
use Storage;

/**
 * User class to handle operator interactions.
 */
class StadiumGeneralSettingService
{
    /**
     * @var predefined logo path
     */
    protected $logoPath,$imagePath;
    /**
     * The user repository instance.
     *
     * @var stadiumGeneralSettingRepository
     */
    private $stadiumGeneralSettingRepository;

    /**
     * Create a new service instance.
     *
     * @param StadiumGeneralSettingRepository $stadiumGeneralSettingRepository
     */
    public function __construct(StadiumGeneralSettingRepository $stadiumGeneralSettingRepository)
    {
        $this->logoPath = config('fanslive.IMAGEPATH.stadium_general_setting_aerial_view_graphic');
        $this->imagePath = config('fanslive.IMAGEPATH.stadium_image');
        $this->stadiumGeneralSettingRepository = $stadiumGeneralSettingRepository;
    }

    /**
     * Handle logic to update a given category.
     *
     * @param $user
     * @param $clubId
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $clubId, $data)
    {
        $stadiumGeneralSettings = StadiumGeneralSetting::where('club_id', $clubId)->first();
        if(isset($data['is_using_allocated_seating']) && $data['is_using_allocated_seating'] == 1) {
    		$data['number_of_seats'] = null;
    	}

        $graphic_image['url'] = null;
        $graphic_image['file_name'] = null;

        if ($stadiumGeneralSettings && isset($data['is_using_allocated_seating']) && $data['is_using_allocated_seating'] == 1) {
            $graphic_image['url'] = $stadiumGeneralSettings->aerial_view_ticketing_graphic;
            $graphic_image['file_name'] = $stadiumGeneralSettings->aerial_view_ticketing_graphic_file_name;
        }

		if (isset($data['aerial_view_ticketing_graphic'])) {
			if ($stadiumGeneralSettings && $stadiumGeneralSettings->aerial_view_ticketing_graphic_file_name) {
				$existingLogo = $this->logoPath.$stadiumGeneralSettings->aerial_view_ticketing_graphic_file_name;
				$disk = Storage::disk('s3');
				$disk->delete($existingLogo);
			}

			$graphic_image = uploadImageToS3($data['aerial_view_ticketing_graphic'], $this->logoPath);
		}
		$data['aerial_view_ticketing_graphic'] = $graphic_image['url'];
		$data['aerial_view_ticketing_graphic_file_name'] = $graphic_image['file_name'];

		$image['url'] = null;
		$image['file_name'] = null;

		if ($stadiumGeneralSettings) {
			$image['url'] = $stadiumGeneralSettings->image;
			$image['file_name'] = $stadiumGeneralSettings->image_file_name;
        }

        if (isset($data['image'])) {
            if ($stadiumGeneralSettings && $stadiumGeneralSettings->image_file_name) {
                $existingImage = $this->imagePath.$stadiumGeneralSettings->image_file_name;
                $disk = Storage::disk('s3');
                $disk->delete($existingImage);
            }

            $image = uploadImageToS3($data['image'], $this->imagePath);
        }
        $data['image'] = $image['url'];
        $data['image_file_name'] = $image['file_name'];

        $geocode = file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.urlencode($data['address'].'+'.$data['address_2'].'+'.$data['town'].'+'.$data['postcode']).'&key='.config('fanslive.GOOGLE_AUTH_KEY.key'));
        $output = json_decode($geocode);
        if ($output->results) {
            $data['latitude'] = $output->results[0]->geometry->location->lat;
            $data['longitude'] = $output->results[0]->geometry->location->lng;
        }

        $stadiumGeneralSettingUpdate = $this->stadiumGeneralSettingRepository->update($user, $clubId, $data);

        return $stadiumGeneralSettingUpdate;
    }
}
