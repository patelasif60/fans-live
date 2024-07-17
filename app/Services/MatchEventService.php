<?php

namespace App\Services;

use App\Repositories\MatchEventRepository;
use Illuminate\Support\Arr;
use Storage;

/**
 * User class to handle operator interactions.
 */
class MatchEventService
{
    /**
     * The match event repository instance.
     *
     * @var repository
     */
    protected $matchEventRepository;

    protected $videoPath;

    /**
     * Create a new service instance.
     *
     * @param MatchEventRepository $matchEventRepository
     */
    public function __construct(MatchEventRepository $matchEventRepository)
    {
        $this->videoPath = config('fanslive.VIDEOPATH.match_event_video');
        $this->matchEventRepository = $matchEventRepository;
    }

    /**
     * Destory/Unset object variables.
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->matchEventRepository);
    }

    /**
     * Handle logic to create a membership package.
     *
     * @param $clubId
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($data)
    {
    }

    /**
     * Handle logic to update a given membership package.
     *
     * @param $user
     * @param $membershipPackage
     * @param $data
     *
     * @return mixed
     */
    public function update($matchId, $data)
    {
        $matchEventOldData = $this->matchEventRepository->getDataWithCondition(['match_id' => $matchId])->pluck('id', 'id')->toArray();
        $dbVideoPath = [];
        $disk = Storage::disk('s3');
        if (Arr::get($data, 'action_replay_video')) {
            foreach ($data['action_replay_video'] as $key => $video) {
                if (Arr::get($data, "event_edit_video_name.$key", 0) && Arr::get($data, "action_replay_video.$key", 0)) { // if video already exist and replace on edit remove from s3 server
                    $disk->delete($this->videoPath.Arr::get($data, "event_edit_video_name.$key"));
                }
                $data['action_replay_video'][$key] = uploadImageToS3($video, $this->videoPath); // upload video here
            }
        }

        if (Arr::get($data, 'match_event')) {
            foreach ($data['match_event'] as $key => $value) {
                $dbData['match_id'] = $matchId;
                $dbData['club_id'] = $value;
                $dbData['minute'] = Arr::get($data, "match_events_time.$key");
                $dbData['event_type'] = Arr::get($data, "match_type_of_event.$key");
                $dbData['player_id'] = Arr::get($data, "match_event_player.$key");
                $dbData['substitute_player_id'] = Arr::get($data, "substitution_player.$key", null);
                $dbData['action_replay_video'] = Arr::get($data, "action_replay_video.$key.url", null);
                $dbData['action_replay_video_file_name'] = Arr::get($data, "action_replay_video.$key.file_name", null);

                if (isset($data['event_edit_ids'][$key])) { // update data
                    $dbVideoPath[$data['event_edit_ids'][$key]] = $this->matchEventRepository->getDataById($data['event_edit_ids'][$key]);
                    if (!$dbData['action_replay_video'] && $dbVideoPath[$data['event_edit_ids'][$key]]) { // if file not upload save database file path and update it
                        $dbData['action_replay_video'] = $dbVideoPath[$data['event_edit_ids'][$key]]['action_replay_video'];
                        $dbData['action_replay_video_file_name'] = $dbVideoPath[$data['event_edit_ids'][$key]]['action_replay_video_file_name'];
                    }
                    $matchEvent = $this->matchEventRepository->update($data['event_edit_ids'][$key], $dbData);
                    unset($matchEventOldData[$data['event_edit_ids'][$key]]);
                } else { // add new entry
                    $matchEvent = $this->matchEventRepository->create($dbData);
                }
            }
        }
        if (!empty($matchEventOldData)) { // delete rest ids
            foreach ($matchEventOldData as $val) {
                $dbData = $this->matchEventRepository->getDataById($val);
                $disk->delete($this->videoPath.$dbData['action_replay_video_file_name']);

                $this->matchEventRepository->delete($val);
            }
        }

        return true;
    }

    public function manageUpload($data)
    {
        if (Arr::get($data, 'action_replay_video')) {
            $disk = Storage::disk('s3');
            foreach ($data['action_replay_video'] as $key => $video) {
                if (Arr::get($data, "event_edit_video_name.$key", 0)) { // if video already exist and replace on edit remove from s3 server
                    $disk->delete($this->videoPath.Arr::get($data, "event_edit_video_name.$key"));
                }
                $data['action_replay_video'][$key] = uploadImageToS3($video, $this->videoPath); // upload video here
            }
        }
    }

    /**
     * Get membership package user data.
     *
     * @param $data
     *
     * @return mixed
     */
    public function getData($data)
    {
        return $this->matchEventRepository->getData($data);
    }
}
