<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Quiz model class for table request.
 */
class QuizFillInTheBlank extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'quiz_fill_in_the_blanks';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'quiz_id', 'hint', 'answer', 'accepted_answer',
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
