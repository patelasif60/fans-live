<?php

namespace App\Repositories;

use App\Models\News;
use Carbon\Carbon;
use DB;

/**
 * Repository class for model.
 */
class NewsRepository extends BaseRepository
{
    /**
     * Get Match data.
     *
     * @param $clubId
     * @param $data
     *
     * @return mixed
     */
    public function getData($clubId, $data)
    {
        $newsData = DB::table('news')->where('club_id', $clubId);

        if (isset($data['sortby'])) {
            $sortby = $data['sortby'];
            $sorttype = $data['sorttype'];
        } else {
            $sortby = 'news.id';
            $sorttype = 'desc';
        }
        $newsData = $newsData->orderBy($sortby, $sorttype);

        if (isset($data['name']) && trim($data['name']) != '') {
            $newsData->where('news.title', 'like', '%'.$data['name'].'%');
        }

        if (!empty($data['from_date'])) {
            $newsData->whereDate('news.publication_date', '>=', convertDateFormat($data['from_date'], config('fanslive.DATE_CMS_FORMAT.php')));
        }
        if (!empty($data['to_date'])) {
            $newsData->whereDate('news.publication_date', '<=', convertDateFormat($data['to_date'], config('fanslive.DATE_CMS_FORMAT.php')));
        }

        $newsListArray = [];
        if (!array_key_exists('pagination', $data)) {
            $newsData = $newsData->paginate($data['pagination_length']);
            $newsListArray = $newsData;
        } else {
            $newsListArray['total'] = $newsData->count();
            $newsListArray['data'] = $newsData->get();
        }

        $response = $newsListArray;

        return $response;
    }

    /**
     * Handle logic to create a news.
     *
     * @param $clubId
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($clubId, $user, $data)
    {
        $news = News::create([
            'club_id'          => $clubId,
            'title'            => $data['name'],
            'image'            => $data['logo'],
            'description'      => $data['notes'],
            'image_file_name'  => $data['logo_file_name'],
            'status'           => $data['status'],
            'publication_date' => convertDateTimezone($data['pubdate'], $data['global_club_timezone'], null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php')),
            'created_by'       => $user->id,
            'updated_by'       => $user->id,
        ]);

        return $news;
    }

    /**
     * Handle logic to update a news.
     *
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $news, $data)
    {
        $news->fill([
            'title'           => $data['name'],
            'image'           => $data['logo'],
            'image_file_name' => $data['logo_file_name'],
            'description'     => $data['notes'],
            'status'          => $data['status'],
            'publication_date'=> convertDateTimezone($data['pubdate'], $data['global_club_timezone'], null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php')),
            'created_by'      => $user->id,
            'updated_by'      => $user->id,
        ]);
        $news->save();

        return $news;
    }
}
