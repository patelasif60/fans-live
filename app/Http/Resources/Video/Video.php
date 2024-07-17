<?php

namespace App\Http\Resources\Video;

use Illuminate\Http\Resources\Json\JsonResource;

class Video extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $consumer = getLoggedinConsumer();
        $date = convertDateTimezone($this->getRawOriginal('publication_date'), 'UTC', $consumer->time_zone);
        $timeAgo = getDateDiff($date, $consumer->time_zone);

        return [
          'id'                          => $this->id,
          'club_id'                     => $this->club_id,
          'title'                       => $this->title,
          'description'                 => $this->description,
          'image'                       => $this->image,
          'image_file_name'             => $this->image_file_name,
          'video'                       => $this->video,
          'video_file_name'             => $this->video_file_name,
          'status'                      => $this->status,
          'publication_date'            => $this->publication_date,
          'time_ago'                    => $timeAgo,
          'is_accessible'               => $this->videoMembershipPackageAccess(),
          'accessible_for'              => $this->membershippackages()->pluck('title')->toArray(),
        ];
    }
}
