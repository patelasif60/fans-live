<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * Quiz model class for table request.
 */
class Quizzes extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'club_id', 'title', 'description', 'image', 'image_file_name', 'status', 'time_limit', 'type', 'publication_date', 'created_by', 'updated_by',
	];
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'quizzes';
	/**
	 * get publication date in format.
	 *
	 * @var date
	 */
	protected $dates = ['created_at', 'updated_at'];

	/**
	 * Get the options for a Quiz.
	 */
	public function quizQuestions()
	{
		return $this->hasMany(\App\Models\QuizzesQuestion::class, 'quiz_id');
	}

	/**
	 * Get the quiz end text
	 */
	public function quizEndTexts()
	{
		return $this->hasMany(\App\Models\QuizEndText::class, 'quiz_id');
	}

	/**
	 * Get the quiz fill in the blanks data
	 */
	public function quizFillInTheBlanks()
	{
		return $this->hasMany(\App\Models\QuizFillInTheBlank::class, 'quiz_id');
	}

	/**
	 * Get the question end text
	 */
	public function quizMultipleChoiceQuestions()
	{
		return $this->hasMany(\App\Models\QuizMultipleChoiceQuestions::class, 'quiz_id');
	}

    /**
     * Get conmuser's answered quizzes.
     *
     *
     * @return string
     */
    public function answeredConsumerQuiz()
    {
        return $this->hasMany(\App\Models\AnsweredConsumerQuiz::class, 'quiz_id');
    }

}
