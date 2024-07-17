<?php

namespace App\Repositories;

use App\Models\QuizMultipleChoiceQuestions;

/**
 * Repository class for model.
 */
class QuizMultipleChoiceQuestionRepository extends BaseRepository
{
	/**
	 * Handle logic to create a quiz multiple choice options.
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function create($data)
	{
		$quizQuestion = QuizMultipleChoiceQuestions::create([
			'quiz_id' => $data['quiz_id'],
			'question' => $data['question'],
			'post_answer_text' => isset($data['post_answer_text']) ? $data['post_answer_text'] : NULL,
			'order' => $data['order'],
		]);
		return $quizQuestion;
	}

	/**
	 * Handle logic to delete a quiz multiple choice options.
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function delete($quizId)
	{
		QuizMultipleChoiceQuestions::where(['quiz_id' => $quizId])->delete();
	}
}
