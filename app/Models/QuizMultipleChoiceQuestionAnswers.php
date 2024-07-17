<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * Quiz Multiple Choice Question Answer model class for table request.
 */
class QuizMultipleChoiceQuestionAnswers extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'quiz_multiple_choice_question_answers';
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'quiz_multiple_choice_question_id', 'answer', 'is_correct'
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

}
