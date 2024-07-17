<?php

namespace App\Repositories;

use App\Models\Poll;
use App\Models\Match;
use App\Models\PollOption;
use Carbon\Carbon;
use DB;

/**
 * Repository class for  model.
 */
class PollRepository extends BaseRepository
{
    /**
     * Handle logic to get poll options.
     *
     * @param $pollId
     *
     * @return mixed
     */
    public function getPollOptions($pollId)
    {
        return PollOption::where('poll_id', $pollId)->get();
    }

    /**
     * Handle logic to create a poll user.
     *
     * @param $clubId
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($clubId, $user, $data)
    {
        $poll = Poll::create([
            'club_id'               => $clubId,
            'title'                 => $data['title'],
            'question'              => $data['question'],
            'status'                => $data['status'],
            'publication_date'      => convertDateTimezone($data['publication_date'], $data['global_club_timezone'], null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php')),
            'closing_date'          => convertDateTimezone($data['closing_date'], $data['global_club_timezone'], null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php')),
            'display_results_date'  => convertDateTimezone($data['display_results_date'], $data['global_club_timezone'], null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php')),
            'associated_match'      => $data['associated_match'],
            'created_by'            => $user->id,
            'updated_by'            => $user->id,
        ]);

        return $poll;
    }

    /**
     * Handle logic to update a poll.
     *
     * @param $user
     * @param $poll
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $poll, $data)
    {
        $poll->fill([
            'title'                 => $data['title'],
            'question'              => $data['question'],
            'status'                => $data['status'],
            'publication_date'      => convertDateTimezone($data['publication_date'], $data['global_club_timezone'], null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php')),
            'closing_date'          => convertDateTimezone($data['closing_date'], $data['global_club_timezone'], null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php')),
            'display_results_date'  => convertDateTimezone($data['display_results_date'], $data['global_club_timezone'], null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php')),
            'associated_match'      => $data['associated_match'],
            'created_by'            => $user->id,
            'updated_by'            => $user->id,
        ]);
        $poll->save();

        return $poll;
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
        $pollData = DB::table('polls')
                    ->leftjoin('matches', 'matches.id', '=', 'polls.associated_match')
                    ->leftjoin('clubs as club_home', 'club_home.id', '=', 'matches.home_team_id')
                    ->leftjoin('clubs as club_away', 'club_away.id', '=', 'matches.away_team_id')
                    ->select('polls.*', 'club_home.id as home_team_id', 'club_away.id as away_team_id', 'club_home.name as home_team_name', 'club_away.name as away_team_name')
                    ->where('polls.club_id', $clubId);

        if (isset($data['sortby'])) {
            $sortby = $data['sortby'];
            $sorttype = $data['sorttype'];
        } else {
            $sortby = 'polls.id';
            $sorttype = 'desc';
        }
        $pollData = $pollData->orderBy($sortby, $sorttype);

        if (isset($data['title']) && trim($data['title']) != '') {
            $pollData->where('polls.title', 'like', '%'.$data['title'].'%');
        }

        if (!empty($data['from_date'])) {
            $pollData->wheredate('polls.publication_date', '>=', convertDateFormat($data['from_date'], config('fanslive.DATE_CMS_FORMAT.php')));
        }

        if (!empty($data['to_date'])) {
            $pollData->wheredate('polls.publication_date', '<=', convertDateFormat($data['to_date'], config('fanslive.DATE_CMS_FORMAT.php')));
        }

        $pollListArray = [];

        if (!array_key_exists('pagination', $data)) {
            $pollData = $pollData->paginate($data['pagination_length']);
            $pollListArray = $pollData;
        } else {
            $pollListArray['total'] = $pollData->get()->count();
            $pollListArray['data'] = $pollData->get();
        }

        $response = $pollListArray;

        return $response;
    }
}
