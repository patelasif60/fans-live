<?php

namespace App\Repositories;
use App\Models\QuizMultipleChoiceQuestionAnswers;

/**
 * Repository class for model.
 */
class QuizMultipleChoiceQuestionAnswerRepository extends BaseRepository
{
	/**
	 * Handle logic to create a quiz multiple choice answer.
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function create($data)
	{
		$quizMultiChoice = QuizMultipleChoiceQuestionAnswers::create([
			'quiz_multiple_choice_question_id' => $data['quiz_multiple_choice_question_id'],
			'answer' => $data['answer'],
			'is_correct' => $data['is_correct'],
		]);
		return $quizMultiChoice;
	}

	/**
	 * Handle logic to delete a quiz multiple choice answer.
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function delete($quizMultipleChoiceQuestionId)
	{
		QuizMultipleChoiceQuestionAnswers::where(['quiz_multiple_choice_question_id' => $quizMultipleChoiceQuestionId])->delete();
	}

}
