<?php

namespace App\Http\Resources\QuizEndText;

use Illuminate\Http\Resources\Json\JsonResource;

class QuizEndText extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
          'id'              => $this->id,
          'end_text'        => $this->end_text,
          'points_threshold'=> $this->points_threshold,
        ];
    }
}
