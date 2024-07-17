<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Quizzes End Texts model class for table request.
 */
class QuizEndText extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'quiz_end_texts';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'quiz_id', 'end_text', 'points_threshold',
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
