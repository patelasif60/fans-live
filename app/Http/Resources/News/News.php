<?php

namespace App\Http\Resources\News;

use Illuminate\Http\Resources\Json\JsonResource;

class News extends JsonResource
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
            'title'            => $this->title,
            'type'             => 'news',
            'description'      => $this->description,
            'plain_description'=> trim(preg_replace('/\s+/', ' ', strip_tags($this->description))),
            'image'            => $this->image,
            'publication_date' => $this->publication_date,
            'time_ago'         => $timeAgo,
            'url'              => config('fanslive.APP_URL').'/news/'.$this->id,
        ];
    }
}
