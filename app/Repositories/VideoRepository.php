<?php

namespace App\Repositories;

use DB;
use App\Models\Video;
use Illuminate\Support\Arr;

/**
 * Repository class for  model.
 */
class VideoRepository extends BaseRepository
{
    /**
     * Handle logic to create a video.
     *
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($clubId, $user, $data)
    {
        $video = Video::create([
            'club_id'           => $clubId,
            'title'             => $data['title'],
            'description'       => $data['description'],
            'image'             => $data['thumbnail'],
            'image_file_name'   => $data['thumbnail_file_name'],
            'video'             => $data['video'],
            'video_file_name'   => $data['video_file_name'],
            'status'            => $data['status'],
            'publication_date'  => convertDateTimezone($data['pubdate'], $data['global_club_timezone'], null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php')),
            'created_by'        => $user->id,
            'updated_by'        => $user->id,
        ]);

        $video->membershippackages()->attach(Arr::get($data,'access'));

        return $video;
    }

    /**
     * Handle logic to update a category.
     *
     * @param $user
     * @param $category
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $video, $data)
    {
        $video->fill([
            'title'             => $data['title'],
            'description'       => $data['description'],
            'image'             => $data['image'],
            'image_file_name'   => $data['image_file_name'],
            'video'             => $data['video'],
            'video_file_name'   => $data['video_file_name'],
            'status'            => $data['status'],
            'publication_date'  => convertDateTimezone($data['pubdate'], $data['global_club_timezone'], null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php')),
            'created_by'        => $user->id,
            'updated_by'        => $user->id,
        ]);

        $video->save();
        $video->membershippackages()->sync(Arr::get($data,'access'));

        return $video;
    }

    /**
     * Get Video data.
     *
     * @param $clubId
     * @param $data
     *
     * @return mixed
     */
    public function getData($clubId, $data)
    {

        $videosData = DB::table('videos')->select(
            'videos.id',
            'videos.title',
            'videos.publication_date',
            'videos.status',
            DB::raw('IF(group_concat(membership_packages.title SEPARATOR ", ") IS NULL or group_concat(membership_packages.title SEPARATOR ", ") = "", "-", group_concat(membership_packages.title SEPARATOR ", ")) as membership_packages_name'))
        ->where('videos.club_id', $clubId)
        ->leftJoin('video_membership_package', function ($join) {
                $join->on('videos.id', '=', 'video_membership_package.video_id');
            })->leftJoin('membership_packages', function ($join) {
                $join->on('membership_packages.id', '=', 'video_membership_package.membership_package_id');
            })->groupBy('videos.id', 'videos.club_id', 'videos.title', 'videos.publication_date', 'videos.status');

        if (isset($data['sortby'])) {
            $sortby = $data['sortby'];
            $sorttype = $data['sorttype'];
        } else {
            $sortby = 'videos.id';
            $sorttype = 'desc';
        }

        $videosData = $videosData->orderBy($sortby, $sorttype);

        if (isset($data['title']) && trim($data['title']) != '') {
            $videosData->where('videos.title', 'like', '%'.$data['title'].'%');
        }

        if (!empty($data['from_date'])) {
            $videosData->wheredate('videos.publication_date', '>=', convertDateFormat($data['from_date'], config('fanslive.DATE_CMS_FORMAT.php')));
        }

        if (!empty($data['to_date'])) {
            $videosData->wheredate('videos.publication_date', '<=', convertDateFormat($data['to_date'], config('fanslive.DATE_CMS_FORMAT.php')));
        }

        $videoListArray = [];
        if (!array_key_exists('pagination', $data)) {
            $videosData = $videosData->paginate($data['pagination_length']);
            $videoListArray = $videosData;
        } else {
            $videoListArray['total'] = $videosData->get()->count();
            $videoListArray['data'] = $videosData->get();
        }

        $response = $videoListArray;

        return $response;
    }
}
