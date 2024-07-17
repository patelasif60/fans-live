<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizMultipleChoiceQuestionAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_multiple_choice_question_answers', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('quiz_multiple_choice_question_id');
			$table->foreign('quiz_multiple_choice_question_id','qmcq_id')->references('id')->on('quiz_multiple_choice_questions')->onDelete('cascade');
			$table->string('answer');
			$table->string('is_correct')->nullable()->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quiz_question_answers');
    }
}
