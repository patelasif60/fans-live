<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizMultipleChoiceQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_multiple_choice_questions', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('quiz_id');
			$table->foreign('quiz_id')->references('id')->on('quizzes')->onDelete('cascade');
			$table->text('question');
            $table->string('post_answer_text')->nullable()->default(NULL);
            $table->unsignedInteger('order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quiz_questions');
    }
}
