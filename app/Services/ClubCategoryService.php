<?php

namespace App\Services;

use App\Repositories\ClubCategoryRepository;
use File;
use Storage;

/**
 * Category class to handle operator interactions.
 */
class ClubCategoryService
{
    /**
     * @var predefined logo path
     */
    protected $logoPath;

    /**
     * The category repository instance.
     *
     * @var repository
     */
    protected $repository;

    /**
     * Create a new service instance.
     *
     * @param ClubCategoryRepository $repository
     */
    public function __construct(ClubCategoryRepository $repository)
    {
        $this->logoPath = config('fanslive.IMAGEPATH.club_category_logo');
        $this->repository = $repository;
    }

    /**
     * Handle logic to create a category.
     *
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($user, $data)
    {
        if (isset($data['logo'])) {
            $logo = uploadImageToS3($data['logo'], $this->logoPath);
        } else {
            $logo['url'] = null;
            $logo['file_name'] = null;
        }
        $data['logo'] = $logo['url'];
        $data['logo_file_name'] = $logo['file_name'];

        $category = $this->repository->create($user, $data);

        return $category;
    }

    /**
     * Handle logic to update a given category.
     *
     * @param $user
     * @param $category
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $category, $data)
    {
        if (isset($data['logo'])) {
            $existingLogo = $this->logoPath.$category->logo_file_name;
            $disk = Storage::disk('s3');
            $disk->delete($existingLogo);

            $logo = uploadImageToS3($data['logo'], $this->logoPath);
        } else {
            $logo['url'] = $category->logo;
            $logo['file_name'] = $category->logo_file_name;
        }
        $data['logo'] = $logo['url'];
        $data['logo_file_name'] = $logo['file_name'];

        $categoryToUpdate = $this->repository->update($user, $category, $data);

        return $categoryToUpdate;
    }

    /**
     * Handle logic to delete a given logo file.
     *
     * @param $category
     *
     * @return mixed
     */
    public function deleteLogo($category)
    {
        $disk = Storage::disk('s3');
        $logo = $this->logoPath.$category->logo_file_name;

        return $disk->delete($logo);
    }

    /**
     * Get Category data.
     *
     * @param $data
     *
     * @return mixed
     */
    public function getData($data)
    {
        $category = $this->repository->getData($data);

        return $category;
    }

	/**
	 * Get club category count.
	 *
	 *
	 * @return mixed
	 */
	public function getClubCategoryCount()
	{
		$clubCategoryCount = $this->repository->getClubCategoryCount();

		return $clubCategoryCount;
	}

}
