<?php

namespace App\Http\Resources\FeedItem;

use Illuminate\Http\Resources\Json\JsonResource;

class FeedItem extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $consumer = getLoggedinConsumer();
        $date = convertDateTimezone($this->getRawOriginal('publication_date'), 'UTC', $consumer->time_zone);
        $timeAgo = getDateDiff($date, $consumer->time_zone);

        return [
            'id'               => $this->id,
            'screen_name'      => $this->contentFeed->screen_name,
            'text'             => $this->text,
            'plain_text'       => trim(preg_replace('/\s+/', ' ', strip_tags($this->text))),
            'status'           => $this->status,
            'media'            => json_decode($this->media),
            'title'            => $this->title,
            'youtube_id'       => $this->youtube_id,
            'feed_url'         => $this->feed_url,
            'publication_date' => $this->publication_date,
            'time_ago'         => $timeAgo,
            'type'             => $this->contentFeed->type,
            'url'              => config('fanslive.APP_URL').'/feeditem/'.$this->id,
        ];
    }
}
