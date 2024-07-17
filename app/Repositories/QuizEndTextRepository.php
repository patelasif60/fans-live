<?php

namespace App\Repositories;

use App\Models\QuizEndText;

/**
 * Repository class for model.
 */
class QuizEndTextRepository extends BaseRepository
{
	/**
	 * Handle logic to create a end text.
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function create($data)
	{
		$quizEndText = QuizEndText::create([
			'quiz_id' => $data['quiz_id'],
			'end_text' => $data['end_text'],
			'points_threshold' => $data['points_threshold'],
		]);
		return $quizEndText;
	}

	/**
	 * Handle logic to delete a end text.
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function delete($quizId)
	{
		$quizEndText = QuizEndText::where(['quiz_id' => $quizId])->delete();
	}

}
