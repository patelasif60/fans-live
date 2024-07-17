<?php

namespace App\Http\Resources\Quiz;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\QuizFillInTheBlank\QuizFillInTheBlank as QuizFillInTheBlank;
use App\Http\Resources\QuizEndText\QuizEndText as QuizEndText;
use App\Http\Resources\QuizMultipleChoiceQuestion\QuizMultipleChoiceQuestion as QuizMultipleChoiceQuestion;

class Quiz extends JsonResource
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
          'id'                            => $this->id,
          'club_id'                       => $this->club_id,
          'title'                         => $this->title,
          'description'                   => $this->description,
          'image'                         => $this->image,
          'image_file_name'               => $this->image_file_name,
          'status'                        => $this->status,
          'type'                          => $this->type,
          'publication_date'              => $this->publication_date,
          'time_limit'                    => $this->time_limit,
          'quiz_fill_in_the_blank'        => QuizFillInTheBlank::collection($this->quizFillInTheBlanks),
          'quiz_end_text'                 => QuizEndText::collection($this->quizEndTexts),
          'quiz_multiple_choice_question' => QuizMultipleChoiceQuestion::collection($this->quizMultipleChoiceQuestions)
        ];
    }
}
