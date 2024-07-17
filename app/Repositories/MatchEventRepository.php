<?php

namespace App\Repositories;

use App\Models\MatchEvent;
use Illuminate\Support\Arr;

/**
 * Repository class for model.
 */
class MatchEventRepository extends BaseRepository
{
    /**
     * Handle logic to create a new match event.
     *
     * @param $data
     *
     * @return mixed
     */
    public function create($data)
    {
        return MatchEvent::create([
            'match_id'                      => $data['match_id'],
            'club_id'                       => $data['club_id'],
            'player_id'                     => $data['player_id'],
            'event_type'                    => $data['event_type'],
            'minute'                        => $data['minute'],
            'extra_time'                    => Arr::get($data, 'extra_time', null),
            'action_replay_video'           => Arr::get($data, 'action_replay_video', null),
            'action_replay_video_file_name' => Arr::get($data, 'action_replay_video_file_name', null),
            'substitute_player_id'          => Arr::get($data, 'substitute_player_id', null),
        ]);
    }

    public function update($Id, $requestData)
    {
        $matchEvent = MatchEvent::find($Id);

        return $matchEvent->fill([
            'match_id'                      => Arr::get($requestData, 'match_id'),
            'club_id'                       => $requestData['club_id'],
            'minute'                        => Arr::get($requestData, 'minute'),
            'player_id'                     => Arr::get($requestData, 'player_id'),
            'event_type'                    => Arr::get($requestData, 'event_type'),
            'extra_time'                    => Arr::get($requestData, 'extra_time', null),
            'substitute_player_id'          => Arr::get($requestData, 'substitute_player_id'),
            'action_replay_video'           => Arr::get($requestData, 'action_replay_video'),
            'action_replay_video_file_name' => Arr::get($requestData, 'action_replay_video_file_name'),
        ])->save();
    }

    /**
     * Handle logic to delete an existing match event.
     *
     * @param $id
     *
     * @return mixed
     */
    public function delete($id)
    {
        return MatchEvent::where('id', $id)->delete();
    }

    /**
     * Handle logic to delete an existing match events by match id.
     *
     * @param $matchId
     *
     * @return mixed
     */
    public function deleteEvent($matchId)
    {
        return MatchEvent::where('match_id', $matchId)->delete();
    }

    public function getDataById($id)
    {
        return MatchEvent::find($id);
    }

    public function getDataWithCondition($where = [])
    {
        return MatchEvent::where($where);
    }
}
