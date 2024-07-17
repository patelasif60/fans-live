<?php

namespace App\Repositories;

use App\Models\Quizzes;
use App\Models\AnsweredConsumerQuiz;
use Carbon\Carbon;
use DB;

/**
 * Repository class for model.
 */
class QuizRepository extends BaseRepository
{
	/**
	 * Get Match data.
	 *
	 * @param $clubId
	 * @param $data
	 *
	 * @return mixed
	 */
	public function getData($clubId, $data)
	{
		$quizData = Quizzes::where('club_id', $clubId);

		if (isset($data['sortby'])) {
			$sortby = $data['sortby'];
			$sorttype = $data['sorttype'];
		} else {
			$sortby = 'quizzes.id';
			$sorttype = 'desc';
		}
		$quizData = $quizData->orderBy($sortby, $sorttype);

		$newsListArray = [];
		if (!array_key_exists('pagination', $data)) {
			$quizData = $quizData->paginate($data['pagination_length']);
			$newsListArray = $quizData;
		} else {
			$quizListArray['total'] = $quizData->count();
			$quizListArray['data'] = $quizData->get();
		}

		$response = $newsListArray;

		return $response;
	}
	/**
	 * Handle logic to create a quizzes.
	 *
	 * @param $clubId
	 * @param $user
	 * @param $data
	 *
	 * @return mixed
	 */
	public function create($clubId, $user, $data)
	{
		$quiz = Quizzes::create([
			'club_id'          => $clubId,
			'title'            => $data['title'],
			'description'      => $data['description'],
			'image'            => $data['image'],
			'image_file_name'  => $data['image_file_name'],
			'status'           => $data['status'],
			'time_limit'       => isset($data['time_limit']) ? $data['time_limit'] : NULL,
			'type'             => $data['type'],
			'publication_date' => convertDateTimezone($data['publication_date'], $data['global_club_timezone'], null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php')),
			'created_by'       => $user->id,
			'updated_by'       => $user->id,
		]);
		return $quiz;
	}

	/**
	 * Handle logic to create a quizzes.
	 *
	 * @param $clubId
	 * @param $user
	 * @param $data
	 *
	 * @return mixed
	 */
	public function update($user, $quiz, $data)
	{
		$quiz->fill([
			'title'            => $data['title'],
			'description'      => $data['description'],
			'image'            => $data['image'],
			'image_file_name'  => $data['image_file_name'],
			'status'           => $data['status'],
			'time_limit'       => isset($data['time_limit']) ? $data['time_limit'] : NULL,
			'type'             => $data['type'],
			'publication_date' => convertDateTimezone($data['publication_date'], $data['global_club_timezone'], null, null, config('fanslive.DATE_TIME_CMS_FORMAT.php')),
			'updated_by'       => $user->id,
		]);
		$quiz->save();
		return $quiz;
	}

	/**
	 * Handle logic to get quizzes.
	 *
	 * @param $consumerId
	 *
	 * @return mixed
	 */
	public function getQuizzes($clubid, $consumerId)
	{
		return Quizzes::where('club_id', $clubid)
								->where('status', 'Published')
								->where('publication_date','<=', Carbon::now())
                                ->whereDoesntHave('answeredConsumerQuiz', function ($q) use($consumerId)
                                {
                                    $q->where('consumer_id', $consumerId);
                                })->get();
	}

	/**
	 * Handle logic to check consumer answered quiz.
	 *
	 * @param $consumerId
	 * @param $quizId
	 *
	 * @return mixed
	 */
	public function checkUserQuiz($consumerId, $quizId)
	{
		return AnsweredConsumerQuiz::where('consumer_id', $consumerId)->where('quiz_id', $quizId)->first();
	}

	/**
	 * Handle logic to submit consumer answered quiz.
	 *
	 * @param $consumerId
	 * @param $quizId
	 *
	 * @return mixed
	 */
	public function submitQuiz($consumerId, $quizId)
	{
		$quiz = new AnsweredConsumerQuiz();
		$quiz->consumer_id = $consumerId;
		$quiz->quiz_id = $quizId;
		$quiz->save();
		return $quiz;
	}

}
