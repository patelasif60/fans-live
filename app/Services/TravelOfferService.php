<?php

namespace App\Services;

use App\Repositories\TravelOfferRepository;
use File;
use Storage;

/**
 * Category class to handle operator interactions.
 */
class TravelOfferService
{
    /**
     * The category repository instance.
     *
     * @var repository
     */
    protected $repository;
    protected $logoPath;

    /**
     * Create a new service instance.
     *
     * @param TravelOfferRepository $repository
     */
    public function __construct(TravelOfferRepository $repository)
    {
        $this->repository = $repository;
        $this->thumbnailPath = config('fanslive.IMAGEPATH.travel_offers_thumbnail');
        $this->bannerPath = config('fanslive.IMAGEPATH.travel_offers_banner');
        $this->iconPath = config('fanslive.IMAGEPATH.travel_offers_icon');
    }

    /**
     * Get travel offer data.
     *
     * @param $clubId
     * @param $data
     *
     * @return mixed
     */
    public function getData($clubId, $data)
    {
        $travelOffers = $this->repository->getData($clubId, $data);

        return $travelOffers;
    }

    /**
     * Handle logic to create a Travel Offers.
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
            $banner = uploadImageToS3($data['logo'], $this->bannerPath);
            $data['banner'] = $banner['url'];
            $data['banner_file_name'] = $banner['file_name'];
        } else {
            $data['banner'] = null;
            $data['banner_file_name'] = null;
        }
        if (isset($data['thumbnail'])) {
            $thumbbnail = uploadImageToS3($data['thumbnail'], $this->thumbnailPath);
            $data['thumbnail'] = $thumbbnail['url'];
            $data['thumbnail_file_name'] = $thumbbnail['file_name'];
        } else {
            $data['thumbnail'] = null;
            $data['thumbnail_file_name'] = null;
        }
        $icon = uploadImageToS3($data['icon'], $this->iconPath);
        $data['icon'] = $icon['url'];
        $data['icon_file_name'] = $icon['file_name'];
        $travelOffers = $this->repository->create($clubId, $user, $data);

        return $travelOffers;
    }

    /**
     * Handle logic to update a given TravelOffers.
     *
     * @param $data
     * @param $id
     *
     * @return mixed
     */
    public function update($user, $travelOffers, $data)
    {
        $disk = Storage::disk('s3');
        if (isset($data['logo'])) {
            $existingLogo = $this->bannerPath.$travelOffers->banner_file_name;
            $disk->delete($existingLogo);

            $banner = uploadImageToS3($data['logo'], $this->bannerPath);
            $data['banner'] = $banner['url'];
            $data['banner_file_name'] = $banner['file_name'];
        } else {
            $data['banner'] = null;
            $data['banner_file_name'] = null;
            if($data['banner_edit'])
            {
                $data['banner'] = $travelOffers->banner;
                $data['banner_file_name'] = $travelOffers->banner_file_name;
            }
        }
        if (isset($data['thumbnail'])) {
            $existingLogo = $this->thumbnailPath.$travelOffers->thumbnail_file_name;
            $disk->delete($existingLogo);

            $thumbbnail = uploadImageToS3($data['thumbnail'], $this->thumbnailPath);
            $data['thumbnail'] = $thumbbnail['url'];
            $data['thumbnail_file_name'] = $thumbbnail['file_name'];
        } else {
            $data['thumbnail'] = null;
            $data['thumbnail_file_name'] = null;
            if($data['thumbnail_edit'])
            {
                $data['thumbnail'] = $travelOffers->thumbnail;
                $data['thumbnail_file_name'] = $travelOffers->thumbnail_file_name;   
            }
        }
        if (isset($data['icon'])) {
            $existingLogo = $this->iconPath.$travelOffers->icon_file_name;
            $disk->delete($existingLogo);

            $icon = uploadImageToS3($data['icon'], $this->iconPath);
            $data['icon'] = $icon['url'];
            $data['icon_file_name'] = $icon['file_name'];
        } else {
            $data['icon'] = $travelOffers->icon;
            $data['icon_file_name'] = $travelOffers->icon_file_name;
        }
        $travelOffersToUpdate = $this->repository->update($user, $travelOffers, $data);

        return $travelOffersToUpdate;
    }

    /**
     * Handle logic to delete a given logo file.
     *
     * @param $data
     * @param $id
     *
     * @return mixed
     */
    public function deleteLogo($travelOffers)
    {
        $disk = Storage::disk('s3');
        $logo = $this->thumbnailPath.$travelOffers->thumbnail_file_name;
        $disk->delete($logo);
        $logo = $this->bannerPath.$travelOffers->banner_file_name;

        return $disk->delete($logo);
    }
}
