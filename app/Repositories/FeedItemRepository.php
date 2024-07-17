<?php

namespace App\Repositories;

use App\Models\FeedItem;
use Carbon\Carbon;
use DB;

/**
 * Repository class for feed item model.
 */
class FeedItemRepository extends BaseRepository
{
    /**
     * Get content feed data.
     *
     * @param $data
     *
     * @return mixed
     */
    public function getData($clubId, $data)
    {
        $feedItemData = DB::table('feed_items')
                ->join('content_feeds', 'content_feeds.id', '=', 'feed_items.content_feed_id')
                ->where('feed_items.club_id', $clubId)
                ->select('feed_items.*', 'content_feeds.name as feed_item_name', 'content_feeds.last_imported as imported_at');

        if (isset($data['sortby'])) {
            $sortby = $data['sortby'];
            $sorttype = $data['sorttype'];
        } else {
            $sortby = 'feed_items.id';
            $sorttype = 'desc';
        }
        $feedItemData = $feedItemData->orderBy($sortby, $sorttype);

        if (isset($data['text']) && trim($data['text']) != '') {
            $feedItemData->where('feed_items.text', 'like', '%'.$data['text'].'%');
        }

        if (isset($data['feed_id']) && trim($data['feed_id']) != '') {
            $feedItemData->where('feed_items.content_feed_id', 'like', '%'.$data['feed_id'].'%');
        }

        if (!empty($data['from_date'])) {
            $feedItemData->whereDate('content_feeds.last_imported', '>=', convertDateFormat($data['from_date'], config('fanslive.DATE_CMS_FORMAT.php')));
        }

        if (!empty($data['to_date'])) {
            $feedItemData->whereDate('content_feeds.last_imported', '<=', convertDateFormat($data['to_date'], config('fanslive.DATE_CMS_FORMAT.php')));
        }

        $feedItemListArray = [];

        if (!array_key_exists('pagination', $data)) {
            $feedItemData = $feedItemData->paginate($data['pagination_length']);
            $feedItemListArray = $feedItemData;
        } else {
            $feedItemListArray['total'] = $feedItemData->count();
            $feedItemListArray['data'] = $feedItemData->get();
        }

        $response = $feedItemListArray;

        return $response;
    }

    /**
     * Handle logic to create a new content feed.
     *
     * @param $clubId
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function create($dbFields)
    {
        $FeedItem = FeedItem::create($dbFields);
        $FeedItem->save();
    }

    /**
     * Handle logic to update a feed item.
     *
     * @param $user
     * @param $contentFeed
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $feedItem, $data)
    {
        $feedItem = FeedItem::where('id', $feedItem)->update(['status' => isset($data['status']) ? $data['status'] : 'Hidden']);

        return $feedItem;
    }
}
