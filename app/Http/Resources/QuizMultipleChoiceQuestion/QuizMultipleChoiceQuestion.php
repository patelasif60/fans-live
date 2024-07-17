<?php

namespace App\Http\Resources\QuizMultipleChoiceQuestion;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\QuizMultipleChoiceQuestionAnswer\QuizMultipleChoiceQuestionAnswer as QuizMultipleChoiceQuestionAnswer;

class QuizMultipleChoiceQuestion extends JsonResource
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
          'id'                                    => $this->id,
          'question'                              => $this->question,
          'post_answer_text'                      => $this->post_answer_text,
          'order'                                 => $this->order,
          'quiz_multiple_choice_question_answers' => QuizMultipleChoiceQuestionAnswer::collection($this->quizMultipleChoiceQuestionAnswers)
        ];
    }
}
