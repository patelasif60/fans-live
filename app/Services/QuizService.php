<?php


namespace App\Services;

use App\Repositories\QuizRepository;
use App\Repositories\QuizQuestionRepository;
use App\Repositories\QuizFilInTheBlankRepository;
use App\Repositories\QuizMultipleChoiceQuestionRepository;
use App\Repositories\QuizMultipleChoiceQuestionAnswerRepository;
use App\Repositories\QuizEndTextRepository;
use App\Models\QuizMultipleChoiceQuestions;
use File;
use Storage;

/**
 * Quiz class to handle operator interactions.
 */
class QuizService
{
	/**
	 * The news repository instance.
	 *
	 * @var repository
	 */
	protected $quizRepository;
	protected $quizFillInTheBlankRepository;
	protected $quizMultipleChoiceQuestionRepository;
	protected $quizMultipleChoiceQuestionAnswerRepository;
	protected $quizEndTextRepository;

	/**
	 * @var predefined image path
	 */
	protected $imagePath;

	/**
	 * Create a new service instance.
	 *
	 * @param QuizRepository $quizRepository
	 */
	public function __construct(QuizRepository $quizRepository, QuizFilInTheBlankRepository $quizFillInTheBlankRepository, QuizMultipleChoiceQuestionRepository $quizMultipleChoiceQuestionRepository, QuizMultipleChoiceQuestionAnswerRepository $quizMultipleChoiceQuestionAnswerRepository, QuizEndTextRepository $quizEndTextRepository)
	{
		$this->quizRepository = $quizRepository;
		$this->quizFillInTheBlankRepository = $quizFillInTheBlankRepository;
		$this->quizMultipleChoiceQuestionRepository = $quizMultipleChoiceQuestionRepository;
		$this->quizMultipleChoiceQuestionAnswerRepository = $quizMultipleChoiceQuestionAnswerRepository;
		$this->quizEndTextRepository = $quizEndTextRepository;
		$this->imagePath = config('fanslive.IMAGEPATH.quiz_image');
	}

	/**
	 * Get quiz data.
	 *
	 * @param $clubId
	 * @param $data
	 *
	 * @return mixed
	 */
	public function getData($clubId, $data)
	{
		$quiz = $this->quizRepository->getData($clubId, $data);

		return $quiz;
	}

	/**
	 * Handle logic to create a quiz.
	 *
	 * @param $clubId
	 * @param $user
	 * @param $data
	 *
	 * @return mixed
	 */
	public function create($clubId, $user, $data)
	{
		if (isset($data['logo'])) {
			$image = uploadImageToS3($data['logo'], $this->imagePath);
		} else {
			$image['url'] = null;
			$image['file_name'] = null;
		}
		$data['image'] = $image['url'];
		$data['image_file_name'] = $image['file_name'];

		$quiz = $this->quizRepository->create($clubId, $user, $data);

		if (!empty($data['fill_in_the_blank']) && $data['type'] == 'fill_in_the_blanks') {
			foreach ($data['fill_in_the_blank'] as $fillInTheBlank) {
				$hintData['quiz_id'] = $quiz->id;
				$hintData['hint'] = $fillInTheBlank['hint'];
				$hintData['answer'] = $fillInTheBlank['answer'];
				$hintData['accepted_answer'] = implode(",",$fillInTheBlank['accepted_answer']);
				$this->quizFillInTheBlankRepository->create($hintData);
			}
		}

		if (!empty($data['end_of_quiz']) && $data['type'] == 'multiple_choice') {
			foreach ($data['end_of_quiz'] as $endOfQuiz) {
				$quizEndTextData['quiz_id'] = $quiz->id;
				$quizEndTextData['end_text'] = $endOfQuiz['text'];
				$quizEndTextData['points_threshold'] = $endOfQuiz['points_thershold'];
				$this->quizEndTextRepository->create($quizEndTextData);
			}
		}

		$questionContents = json_decode($data['addQuestionContent']['0']);
		if (!empty($questionContents) && $data['type'] == 'multiple_choice') {
			$i = 1;
			foreach ($questionContents as $questionContent) {
				$questionData['quiz_id'] = $quiz->id;
				$questionData['question'] = $questionContent->content_question;
				$questionData['post_answer_text'] = $questionContent->content_post_answer_text;
				$questionData['order'] = $i;
				$quizMultipleChoiceQuestion = $this->quizMultipleChoiceQuestionRepository->create($questionData);
				if($quizMultipleChoiceQuestion) {
					foreach ($questionContent->answers as $answer) {
						$questionAnswerData = [];
						$questionAnswerData['quiz_multiple_choice_question_id'] = $quizMultipleChoiceQuestion->id;
						$questionAnswerData['answer'] = $answer->answer;
						$questionAnswerData['is_correct'] = $answer->is_correct;
						$this->quizMultipleChoiceQuestionAnswerRepository->create($questionAnswerData);
					}
				}
				$i++;
			}
		}
		return $quiz;
	}

	/**
	 * Handle logic to update a given quiz.
	 *
	 * @param $user
	 * @param $quiz
	 * @param $data
	 *
	 * @return mixed
	 */
	public function update($user, $quiz, $data)
	{
		if (isset($data['logo'])) {
			$existingImage = $this->imagePath.$quiz->image_file_name;
			$disk = Storage::disk('s3');
			$disk->delete($existingImage);

			$image = uploadImageToS3($data['logo'], $this->imagePath);
		} else {
			$image['url'] = $quiz->image;
			$image['file_name'] = $quiz->image_file_name;
		}

		$data['image'] = $image['url'];
		$data['image_file_name'] = $image['file_name'];

		$quizToUpdate = $this->quizRepository->update($user, $quiz, $data);

		$this->quizFillInTheBlankRepository->delete($quiz->id);
		if (!empty($data['fill_in_the_blank']) && $data['type'] == 'fill_in_the_blanks') {
			foreach ($data['fill_in_the_blank'] as $fillInTheBlank) {
				$hintData['quiz_id'] = $quiz->id;
				$hintData['hint'] = $fillInTheBlank['hint'];
				$hintData['answer'] = $fillInTheBlank['answer'];
				$hintData['accepted_answer'] = implode(",",$fillInTheBlank['accepted_answer']);
				$this->quizFillInTheBlankRepository->create($hintData);
			}
		}

		$this->quizEndTextRepository->delete($quiz->id);
		if (!empty($data['end_of_quiz']) && $data['type'] == 'multiple_choice') {
			foreach ($data['end_of_quiz'] as $endOfQuiz) {
				$quizEndTextData['quiz_id'] = $quiz->id;
				$quizEndTextData['end_text'] = $endOfQuiz['text'];
				$quizEndTextData['points_threshold'] = $endOfQuiz['points_thershold'];
				$this->quizEndTextRepository->create($quizEndTextData);
			}
		}

		$quizMultipleChoiceQuestions = QuizMultipleChoiceQuestions::where(['quiz_id' => $quiz->id])->get();
		if ($quizMultipleChoiceQuestions) {
			foreach ($quizMultipleChoiceQuestions as $quizMultipleChoiceQuestion) {
				$this->quizMultipleChoiceQuestionAnswerRepository->delete($quizMultipleChoiceQuestion->id);
			}
		}
		$quizMultipleChoiseQuestion = $this->quizMultipleChoiceQuestionRepository->delete($quiz->id);			
		$questionContents = json_decode($data['addQuestionContent']['0']);
		if (!empty($questionContents) && $data['type'] == 'multiple_choice') {
			$i = 1;
			foreach ($questionContents as $questionContent) {
				$questionData['quiz_id'] = $quiz->id;
				$questionData['question'] = $questionContent->content_question;
				$questionData['post_answer_text'] = $questionContent->content_post_answer_text;
				$questionData['order'] = $i;
				$quizMultipleChoiceQuestion = $this->quizMultipleChoiceQuestionRepository->create($questionData);
				if($quizMultipleChoiceQuestion) {
					foreach ($questionContent->answers as $answer) {
						$questionAnswerData = [];
						$questionAnswerData['quiz_multiple_choice_question_id'] = $quizMultipleChoiceQuestion->id;
						$questionAnswerData['answer'] = $answer->answer;
						$questionAnswerData['is_correct'] = $answer->is_correct;
						$this->quizMultipleChoiceQuestionAnswerRepository->create($questionAnswerData);
					}
				}
				$i++;
			}
		}

		return $quizToUpdate;
	}

	/**
	 * Handle logic to delete a given image file.
	 *
	 * @param $quiz
	 *
	 * @return mixed
	 */
	public function deleteImage($quiz)
	{
		$disk = Storage::disk('s3');
		$image = $this->imagePath . $quiz->image_file_name;

		return $disk->delete($image);
	}

	/**
	 * Implement json for multiple choice quetion with answer
	 *
	 * @param $quizMultipleChoiseQuestions (Object)
	 *
	 * @return json
	 */
	public function createQuizMultipleChoiceQuestionAnswersJson($quizId)
	{
		$quizMultipleChoiseQuestions = QuizMultipleChoiceQuestions::with(['quizMultipleChoiceQuestionAnswers' => function($q) {
            $q->select('quiz_multiple_choice_question_id', 'answer', 'is_correct');
        }])->where(['quiz_id' => $quizId])->get();
        
        $quizMultipleChoiseQuestionsArr = [];
        if ($quizMultipleChoiseQuestions) {
            foreach ($quizMultipleChoiseQuestions as $quizMultipleChoiseQuestion) {
                $tempQuizMultipleChoiseQuestion = [];
                $tempQuizMultipleChoiseQuestion['content_question'] = $quizMultipleChoiseQuestion->question;
                $tempQuizMultipleChoiseQuestion['content_post_answer_text'] = $quizMultipleChoiseQuestion->post_answer_text;
                $tempQuizMultipleChoiseQuestion['answers'] = [];
                if (!empty($quizMultipleChoiseQuestion->quizMultipleChoiceQuestionAnswers)) {
                    $tempQuizMultipleChoiseQuestion['answers'] = $quizMultipleChoiseQuestion->quizMultipleChoiceQuestionAnswers->makeHidden('quiz_multiple_choice_question_id')->toArray();
                }
                $quizMultipleChoiseQuestionsArr[] = $tempQuizMultipleChoiseQuestion;
            }
        }

        $quizMultipleChoiseQuestionsJson = '';
        if ($quizMultipleChoiseQuestionsArr) {
            $quizMultipleChoiseQuestionsJson = json_encode($quizMultipleChoiseQuestionsArr);
        }

        return $quizMultipleChoiseQuestionsJson;
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
		return $this->quizRepository->getQuizzes($clubid, $consumerId);
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
		return $this->quizRepository->checkUserQuiz($consumerId, $quizId);
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
		return $this->quizRepository->submitQuiz($consumerId, $quizId);
	}

	/**
	 * unset class instance or public property.
	 */
	public function __destruct()
	{
		unset($this->quizRepository);
		unset($this->imagePath);
	}
}
