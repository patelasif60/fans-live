<?php

namespace App\Http\Resources\QuizFillInTheBlank;

use Illuminate\Http\Resources\Json\JsonResource;

class QuizFillInTheBlank extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $acceptedAnswers = explode(',', $this->accepted_answer);
        array_push($acceptedAnswers, $this->answer);
        return [
          'id'              => $this->id,
          'hint'            => $this->hint,
          'answer'          => $this->answer,
          'accepted_answer' => array_values(array_unique($acceptedAnswers)),
        ];
    }
}
