<?php

namespace App\Services;

use Storage;
use App\Repositories\VideoRepository;

/**
 * Video class to handle operator interactions.
 */
class VideoService
{
    /**
     * The Video repository instance.
     *
     * @var repository
     */
    protected $repository;

    /**
     * The video path.
     *
     * @var videoPath
     */
    protected $videoPath;


    /**
     * The video thumbnail path.
     *
     * @var videoThumbnailPath
     */
    protected $videoThumbnailPath;

    /**
     * Create a new service instance.
     *
     * @param VideoRepository $repository
     */
    public function __construct(VideoRepository $repository)
    {
        $this->repository = $repository;
        $this->videoPath = config('fanslive.IMAGEPATH.video');
        $this->videoThumbnailPath = config('fanslive.IMAGEPATH.video_thumbnail');
    }

    /**
     * Handle logic to create a video.
     *
     * @param $clubId
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($clubId, $user, $data)
    {
        if (isset($data['thumbnail'])) {
            $thumbnail = uploadImageToS3($data['thumbnail'], $this->videoThumbnailPath);
            $data['thumbnail'] = $thumbnail['url'];
            $data['thumbnail_file_name'] = $thumbnail['file_name'];
        } else {
            $data['thumbnail'] = null;
            $data['thumbnail_file_name'] = null;
        }

        if (isset($data['video'])) {
            $video = uploadImageToS3($data['video'], $this->videoPath);
            $data['video'] = $video['url'];
            $data['video_file_name'] = $video['file_name'];
        } else {
            $data['video'] = null;
            $data['video_file_name'] = null;
        }

        $video = $this->repository->create($clubId, $user, $data);

        return $video;
    }

    /**
     * Handle logic to update a given video.
     *
     * @param $user
     * @param $category
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $video, $data)
    {
        $disk = Storage::disk('s3');
        if (isset($data['thumbnail'])) {
            $existingImage = $this->videoThumbnailPath.$video->image_file_name;
            $disk->delete($existingImage);
            $thumbnail = uploadImageToS3($data['thumbnail'], $this->videoThumbnailPath);
            $data['image'] = $thumbnail['url'];
            $data['image_file_name'] = $thumbnail['file_name'];
        } else {
            $data['image'] = $video->image;
            $data['image_file_name'] = $video->image_file_name;
        }

        if (isset($data['video'])) {
            $existingVideo = $this->videoPath.$video->video_file_name;
            $disk->delete($existingVideo);
            $videoS3 = uploadImageToS3($data['video'], $this->videoThumbnailPath);
            $data['video'] = $videoS3['url'];
            $data['video_file_name'] = $videoS3['file_name'];
        } else {
            $data['video'] = $video->video;
            $data['video_file_name'] = $video->video_file_name;
        }

        $video = $this->repository->update($user, $video, $data);

        return $video;
    }

    /**
     * Get videos data.
     *
     * @param $clubId
     * @param $data
     *
     * @return mixed
     */
    public function getData($clubId, $data)
    {
        $videos = $this->repository->getData($clubId, $data);

        return $videos;
    }

    /**
     * Handle logic to delete a given asset file.
     *
     * @param $video
     * @param $id
     *
     * @return mixed
     */
    public function deleteAsset($video)
    {
        $disk = Storage::disk('s3');
        $image = $this->videoThumbnailPath.$video->image_file_name;
        $video = $this->videoPath.$video->video_file_name;

        $disk->delete($image);

        return $disk->delete($video);
    }

    /**
     * unset class instance or public property.
     */
    public function __destruct()
    {
        unset($this->repository);
        unset($this->videoThumbnailPath);
        unset($this->videoPath);
    }
}