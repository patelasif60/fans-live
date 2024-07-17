<?php

namespace App\Repositories;

use DB;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use App\Models\PushNotification;
use App\Models\PushNotificationHistory;
use App\Models\PushNotificationHistoryConsumerStatus;

/**
 * Repository class for  model.
 */
class PushNotificationRepository extends BaseRepository
{
    /**
     * Handle logic to create a push notification.
     *
     * @param $clubId
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($clubId, $user, $data)
    {
        $pushNotification = PushNotification::create([
            'club_id'                            => $clubId,
            'title'                              => $data['title'],
            'message'                            => $data['message'],
            'swipe_action_category'              => $data['swipe_action_category'],
            'swipe_action_item'                  => $data['swipe_action_item'],
            'send_to_user_attending_this_match'  => $data['send_to_user_attending_this_match'],
            'publication_date'                   => convertDateTimezone($data['publication_date'], $data['global_club_timezone'], null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php')),
            'created_by'                         => $user->id,
            'updated_by'                         => $user->id,
        ]);

        $pushNotification->membershippackages()->attach(Arr::get($data,'membership_level'));

        return $pushNotification;
    }

    /**
     * Handle logic to update a push notification.
     *
     * @param $user
     * @param $pushNotification
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $pushNotification, $data)
    {
        $pushNotification->fill([
            'title'                              => $data['title'],
            'message'                            => $data['message'],
            'swipe_action_category'              => $data['swipe_action_category'],
            'swipe_action_item'                  => $data['swipe_action_item'],
            'send_to_user_attending_this_match'  => $data['send_to_user_attending_this_match'],
            'publication_date'                   => convertDateTimezone($data['publication_date'], $data['global_club_timezone'], null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php')),
            'created_by'                         => $user->id,
            'updated_by'                         => $user->id,
        ]);
        $pushNotification->save();
        $pushNotification->membershippackages()->sync(Arr::get($data,'membership_level'));

        return $pushNotification;
    }

    /**
     * Get Poll data.
     *
     * @param $clubId
     * @param $data
     *
     * @return mixed
     */
    public function getData($clubId, $data)
    {
        $pushNotificationData = DB::table('push_notifications')
                    ->leftjoin('matches', 'matches.id', '=', 'push_notifications.send_to_user_attending_this_match')
                    ->leftjoin('clubs as club_home', 'club_home.id', '=', 'matches.home_team_id')
                    ->leftjoin('clubs as club_away', 'club_away.id', '=', 'matches.away_team_id')
                    ->select('push_notifications.*', 'club_home.id as home_team_id', 'club_away.id as away_team_id', 'club_home.name as home_team_name', 'club_away.name as away_team_name')
                    ->where('push_notifications.club_id', $clubId);

        if (isset($data['sortby'])) {
            $sortby = $data['sortby'];
            $sorttype = $data['sorttype'];
        } else {
            $sortby = 'push_notifications.id';
            $sorttype = 'desc';
        }
        $pushNotificationData = $pushNotificationData->orderBy($sortby, $sorttype);

        if (isset($data['title']) && trim($data['title']) != '') {
            $pushNotificationData->where('push_notifications.title', 'like', '%'.$data['title'].'%');
        }

        if (isset($data['message']) && trim($data['message']) != '') {
            $pushNotificationData->where('push_notifications.message', 'like', '%'.$data['message'].'%');
        }

        if (!empty($data['from_date'])) {
            $pushNotificationData->wheredate('push_notifications.publication_date', '>=', convertDateFormat($data['from_date'], config('fanslive.DATE_CMS_FORMAT.php')));
        }

        if (!empty($data['to_date'])) {
            $pushNotificationData->wheredate('push_notifications.publication_date', '<=', convertDateFormat($data['to_date'], config('fanslive.DATE_CMS_FORMAT.php')));
        }

        $pushNotificationListArray = [];

        if (!array_key_exists('pagination', $data)) {
            $pushNotificationData = $pushNotificationData->paginate($data['pagination_length']);
            $pushNotificationListArray = $pushNotificationData;
        } else {
            $pushNotificationListArray['total'] = $pushNotificationData->count();
            $pushNotificationListArray['data'] = $pushNotificationData->get();
        }

        $response = $pushNotificationListArray;

        return $response;
    }

    /**
     * Handle logic to create a push notification history.
     *
     * @param $data
     *
     * @return mixed
     */
    public function createHistory($data)
    {
        $pushNotificationHistory = PushNotificationHistory::create([
            'push_notification_id'      => $data['push_notification_id'],
            'number_of_success'         => $data['number_of_success'],
            'number_of_failure'         => $data['number_of_failure'],
            'number_of_modifications'   => $data['number_of_modification'],
        ]);
        return $pushNotificationHistory;
    }

    /**
     * Handle logic to create a push notification history consumer status.
     *
     * @param $pushNotificationHistoryId
     * @param $consumerWithDeviceTokens
     * @param $failedTokens
     *
     * @return mixed
     */
    public function createHistoryConsumerStatus($pushNotificationHistoryId, $consumerId, $status = FALSE)
    {
        $pushNotificationHistoryConsumerStatus = PushNotificationHistoryConsumerStatus::create([
            'push_notification_history_id'  => $pushNotificationHistoryId,
            'consumer_id'                   => $consumerId,
            'status'                        => $status
        ]);
        return $pushNotificationHistoryConsumerStatus;
    }
}
