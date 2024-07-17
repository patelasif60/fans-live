<?php

namespace App\Services;

use App\Repositories\CTARepository;
use Storage;

/**
 * User class to handle operator interactions.
 */
class CTAService
{
    /**
     * The CTA repository instance.
     *
     * @var ctaRepository
     */
    private $ctaRepository;

    /**
     * @var predefined image path
     */
    protected $imagePath;

    /**
     * Create a new service instance.
     *
     * @param CTARepository $ctaRepository
     */
    public function __construct(CTARepository $ctaRepository)
    {
        $this->ctaRepository = $ctaRepository;
        $this->imagePath = config('fanslive.IMAGEPATH.cta_image');
    }

    /**
     * Destory/Unset object variables.
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->ctaRepository);
        unset($this->imagePath);
    }

    /**
     * Handle logic to create a CTA.
     *
     * @param $clubId
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($clubId, $user, $data)
    {
        if (isset($data['image'])) {
            $image = uploadImageToS3($data['image'], $this->imagePath);
        } else {
            $image['url'] = null;
            $image['file_name'] = null;
        }
        $data['image'] = $image['url'];
        $data['image_file_name'] = $image['file_name'];

        $cta = $this->ctaRepository->create($clubId, $user, $data);

        return $cta;
    }

    /**
     * Handle logic to update a given CTA.
     *
     * @param $user
     * @param $cta
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $cta, $data)
    {
        if (isset($data['image'])) {
            $existingLogo = $this->imagePath.$cta->logo_file_name;
            $disk = Storage::disk('s3');
            $disk->delete($existingLogo);

            $image = uploadImageToS3($data['image'], $this->imagePath);
        } else {
            $image['url'] = $cta->image;
            $image['file_name'] = $cta->image_file_name;
        }
        $data['image'] = $image['url'];
        $data['image_file_name'] = $image['file_name'];

        $ctaToUpdate = $this->ctaRepository->update($user, $cta, $data);

        return $ctaToUpdate;
    }

    /**
     * Handle logic to delete a given image file.
     *
     * @param $cta
     *
     * @return mixed
     */
    public function deleteLogo($cta)
    {
        $disk = Storage::disk('s3');
        $image = $this->imagePath.$cta->image_file_name;

        return $disk->delete($image);
    }

    /**
     * Get CTA data.
     *
     * @param $clubId
     * @param $data
     *
     * @return mixed
     */
    public function getData($clubId, $data)
    {
        $cta = $this->ctaRepository->getData($clubId, $data);

        return $cta;
    }
}
