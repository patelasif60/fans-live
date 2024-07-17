<?php

namespace App\Repositories;

use App\Models\ContentFeed;
use DB;

/**
 * Repository class for content feed model.
 */
class ContentFeedRepository extends BaseRepository
{
    /**
     * Handle logic to create a new content feed.
     *
     * @param $clubId
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($clubId, $user, $data)
    {
        $contentFeed = ContentFeed::create([
            'club_id'                        => $clubId,
            'type'                           => $data['type'],
            'name'                           => $data['name'],
            'api_app_id'                     => isset($data['api_app_id']) ? $data['api_app_id'] : null,
            'api_key'                        => isset($data['api_key']) ? $data['api_key'] : null,
            'api_secret_key'                 => isset($data['api_secret_key']) ? $data['api_secret_key'] : null,
            'api_token'                      => isset($data['api_token']) ? $data['api_token'] : null,
            'api_token_secret_key'           => isset($data['api_token_secret_key']) ? $data['api_token_secret_key'] : null,
            'api_channel_id'                 => isset($data['api_channel_id']) ? $data['api_channel_id'] : null,
            'rss_url'                        => isset($data['rss_url']) ? $data['rss_url'] : null,
            'screen_name'                    => isset($data['screen_name']) ? $data['screen_name'] : null,
            'is_automatically_publish_items' => isset($data['automatically_publish_items']) ? $data['automatically_publish_items'] : 0,
            'created_by'                     => $user->id,
            'updated_by'                     => $user->id,
        ]);

        return $contentFeed;
    }

    /**
     * Handle logic to update a category.
     *
     * @param $user
     * @param $contentFeed
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $contentFeed, $data)
    {
        $contentFeed->fill([
            'type'                           => $data['type'],
            'name'                           => $data['name'],
            'api_app_id'                     => isset($data['api_app_id']) ? $data['api_app_id'] : null,
            'api_key'                        => isset($data['api_key']) ? $data['api_key'] : null,
            'api_secret_key'                 => isset($data['api_secret_key']) ? $data['api_secret_key'] : null,
            'api_token'                      => isset($data['api_token']) ? $data['api_token'] : null,
            'api_token_secret_key'           => isset($data['api_token_secret_key']) ? $data['api_token_secret_key'] : null,
            'api_channel_id'                 => isset($data['api_channel_id']) ? $data['api_channel_id'] : null,
            'rss_url'                        => isset($data['rss_url']) ? $data['rss_url'] : null,
            'screen_name'                    => isset($data['screen_name']) ? $data['screen_name'] : null,
            'is_automatically_publish_items' => isset($data['automatically_publish_items']) ? $data['automatically_publish_items'] : 0,
            'created_by'                     => $user->id,
            'updated_by'                     => $user->id,
            'last_inserted_data'             => $data['last_inserted_data'],
        ]);
        $contentFeed->save();

        return $contentFeed;
    }

    /**
     * Get content feed data.
     *
     * @param $data
     *
     * @return mixed
     */
    public function getData($clubId, $data)
    {
        $contentFeedData = DB::table('content_feeds')->where('club_id', $clubId);

        if (isset($data['sortby'])) {
            $sortby = $data['sortby'];
            $sorttype = $data['sorttype'];
        } else {
            $sortby = 'content_feeds.id';
            $sorttype = 'desc';
        }
        $contentFeedData = $contentFeedData->orderBy($sortby, $sorttype);

        $contentFeedListArray = [];

        if (!array_key_exists('pagination', $data)) {
            $contentFeedData = $contentFeedData->paginate($data['pagination_length']);
            $contentFeedListArray = $contentFeedData;
        } else {
            $contentFeedListArray['total'] = $contentFeedData->count();
            $contentFeedListArray['data'] = $contentFeedData->get();
        }

        $response = $contentFeedListArray;

        return $response;
    }
}
