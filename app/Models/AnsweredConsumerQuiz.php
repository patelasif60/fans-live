<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnsweredConsumerQuiz extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'answered_consumer_quiz';

    /**
     * Disable timestamps.
     *
     * @var array
     */
    public $timestamps = false;
}
