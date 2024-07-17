<?php

namespace App\Http\Resources\QuizMultipleChoiceQuestionAnswer;

use Illuminate\Http\Resources\Json\JsonResource;

class QuizMultipleChoiceQuestionAnswer extends JsonResource
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
          'id'          => $this->id,
          'answer'      => $this->answer,
          'is_correct'  => $this->is_correct,
        ];
    }
}
