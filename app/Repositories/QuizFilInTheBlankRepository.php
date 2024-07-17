<?php

namespace App\Repositories;
use App\Models\QuizFillInTheBlank;


/**
 * Repository class for model.
 */
class QuizFilInTheBlankRepository extends BaseRepository
{
	/**
	 * Handle logic to create a fill in the blanks.
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function create($data)
	{
		$quizBlank = QuizFillInTheBlank::create([
			'quiz_id' => $data['quiz_id'],
			'hint' => $data['hint'],
			'answer' => $data['answer'],
			'accepted_answer' => $data['accepted_answer'],
		]);
		return $quizBlank;
	}

	/**
	 * Handle logic to delete a fill in the blanks.
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function delete($quizId)
	{
		$quizBlank = QuizFillInTheBlank::where(['quiz_id' => $quizId])->delete();
	}
}
