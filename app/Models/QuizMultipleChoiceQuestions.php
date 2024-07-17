<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Quizzes Question answer model class for table request.
 */
class QuizMultipleChoiceQuestions extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'quiz_multiple_choice_questions';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'quiz_id', 'question', 'post_answer_text', 'order'
	];

	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id'];

	/**
	 * Disable timestamps.
	 *
	 * @var array
	 */
	public $timestamps = false;

	/**
     * Get a quiz multiple choise question answers.
     */
    public function quizMultipleChoiceQuestionAnswers()
    {
        return $this->hasMany(\App\Models\QuizMultipleChoiceQuestionAnswers::class, 'quiz_multiple_choice_question_id');
    }

}
