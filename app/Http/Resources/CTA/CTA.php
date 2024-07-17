<?php

namespace App\Http\Resources\CTA;

use App\Models\Category;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Category\Category as CategoryResource;

class CTA extends JsonResource
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
            'image'            => $this->image,
            'button1_text'     => $this->button1_text,
            'button1_action'   => $this->button1_action,
            'button1_item'     => $this->button1_item,
            'button1_item_details'  => $this->button1_item !== null ? new CategoryResource(Category::find($this->button1_item)) : null,
            'button2_text'     => $this->button2_text,
            'button2_action'   => $this->button2_action,
            'button2_item'     => $this->button2_item,
            'button2_item_details'  => $this->button2_item !== null ? new CategoryResource(Category::find($this->button2_item)) : null,
            'publication_date' => $this->publication_date,
            'time_ago'         => $timeAgo,
            'status'           => $this->status,
        ];
    }
}
