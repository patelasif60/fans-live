<?php

namespace App\Services;

use App\Repositories\NewsRepository;
use File;
use Storage;

/**
 * News class to handle operator interactions.
 */
class NewsService
{
    /**
     * The news repository instance.
     *
     * @var repository
     */
    protected $repository;

    /**
     * The news image path.
     *
     * @var logoPath
     */
    protected $logoPath;

    /**
     * Create a new service instance.
     *
     * @param NewsRepository $repository
     */
    public function __construct(NewsRepository $repository)
    {
        $this->repository = $repository;
        $this->logoPath = config('fanslive.IMAGEPATH.news_logo');
    }

    /**
     * Get news data.
     *
     * @param $clubId
     * @param $data
     *
     * @return mixed
     */
    public function getData($clubId, $data)
    {
        $news = $this->repository->getData($clubId, $data);

        return $news;
    }

    /**
     * Handle logic to create a news.
     *
     * @param $clubId
     * @param $data
     *
     * @return mixed
     */
    public function create($clubId, $user, $data)
    {
        if (isset($data['logo'])) {
            $logo = uploadImageToS3($data['logo'], $this->logoPath);
            $data['logo'] = $logo['url'];
            $data['logo_file_name'] = $logo['file_name'];
        } else {
            $data['logo'] = null;
            $data['logo_file_name'] = null;
        }
        $news = $this->repository->create($clubId, $user, $data);

        return $news;
    }

    /**
     * Handle logic to update a given news.
     *
     * @param $user
     * @param $news
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $news, $data)
    {
        $disk = Storage::disk('s3');
        if (isset($data['logo'])) {
            $existingLogo = $this->logoPath.$news->image_file_name;
            $disk->delete($existingLogo);
            $logo = uploadImageToS3($data['logo'], $this->logoPath);
            $data['logo'] = $logo['url'];
            $data['logo_file_name'] = $logo['file_name'];
        } else {
            $data['logo'] = null;
            $data['logo_file_name'] = null;
            if($data['logo_edit']){
                    $data['logo'] = $news->image;
                    $data['logo_file_name'] = $news->image_file_name;
            }
        }
        $newsToUpdate = $this->repository->update($user, $news, $data);

        return $newsToUpdate;
    }

    /**
     * Handle logic to delete a given logo file.
     *
     * @param $news
     * @param $id
     *
     * @return mixed
     */
    public function deleteLogo($news)
    {
        $disk = Storage::disk('s3');
        $logo = $this->logoPath.$news->image_file_name;

        return $disk->delete($logo);
    }

    /**
     * unset class instance or public property.
     */
    public function __destruct()
    {
        unset($this->repository);
        unset($this->logoPath);
    }
}
