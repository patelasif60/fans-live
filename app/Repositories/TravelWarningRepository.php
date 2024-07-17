<?php

namespace App\Repositories;

use App\Models\TravelWarning;
use App\Models\Consumer;
use Carbon\Carbon;
use DB;

class TravelWarningRepository extends BaseRepository
{
    /**
     * Get TravelWarnings data.
     *
     * @param $clubId
     * @param $data
     *
     * @return mixed
     */
    public function getData($clubId, $data)
    {
        $travelWarningsData = DB::table('travel_warnings')->where('club_id', $clubId);

        if (isset($data['sortby'])) {
            $sortby = $data['sortby'];
            $sorttype = $data['sorttype'];
        } else {
            $sortby = 'travel_warnings.id';
            $sorttype = 'desc';
        }
        $travelWarningsData = $travelWarningsData->orderBy($sortby, $sorttype);

        if (isset($data['text']) && trim($data['text']) != '') {
            $travelWarningsData->where('travel_warnings.text', 'like', '%'.$data['text'].'%');
        }

        if (!empty($data['fromdate'])) {
            $travelWarningsData->whereDate('travel_warnings.publication_date_time', '>=', convertDateFormat($data['fromdate'], config('fanslive.DATE_CMS_FORMAT.php')));
        }

        if (!empty($data['todate'])) {
            $travelWarningsData->whereDate('travel_warnings.show_until', '<=', convertDateFormat($data['todate'], config('fanslive.DATE_CMS_FORMAT.php')));
        }

        $travelWarningsListArray = [];
        if (!array_key_exists('pagination', $data)) {
            $travelWarningsData = $travelWarningsData->paginate($data['pagination_length']);
            $travelWarningsListArray = $travelWarningsData;
        } else {
            $travelWarningsListArray['total'] = $travelWarningsData->count();
            $travelWarningsListArray['data'] = $travelWarningsData->get();
        }

        $response = $travelWarningsListArray;

        return $response;
    }

    /**
     * Handle logic to create a TravelWarnings.
     *
     * @param $clubId
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($clubId, $user, $data)
    {
        $travelWarnings = new TravelWarning();
        $travelWarnings->club_id = $clubId;
        $travelWarnings->text = $data['text'];
        $travelWarnings->color = $data['color'];
        $travelWarnings->status = $data['status'];
        $travelWarnings->publication_date_time = convertDateTimezone($data['publication_date_time'], $data['global_club_timezone'], null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php'));
        $travelWarnings->show_until = convertDateTimezone($data['show_until'], $data['global_club_timezone'], null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php'));
        $travelWarnings->created_by = $user->id;

        return $travelWarnings->save();
    }

    /**
     * Handle logic to update a TravelWarnings.
     *
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $travelWarnings, $data)
    {
        $travelWarnings->text = $data['text'];
        $travelWarnings->color = $data['color'];
        $travelWarnings->status = $data['status'];
        $travelWarnings->publication_date_time = convertDateTimezone($data['publication_date_time'], $data['global_club_timezone'], null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php'));
        $travelWarnings->show_until = convertDateTimezone($data['show_until'], $data['global_club_timezone'], null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php'));
        $travelWarnings->updated_by = $user->id;

        return $travelWarnings->save();
    }

    /**
     * Handle logic to get Travel Warnings.
     *
     * @param $userId
     *
     * @return mixed
     */
    public function getTravelWarnings($userId)
    {
        $consumer = Consumer::where('user_id', $userId)->first();
        return TravelWarning::where('club_id', $consumer->club_id)->where('publication_date_time', '<=', now())->where('show_until', '>=', now())->where('status', 'Published')->get();
    }
}
