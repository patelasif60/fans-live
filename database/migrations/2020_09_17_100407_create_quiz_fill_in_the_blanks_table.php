<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizFillInTheBlanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_fill_in_the_blanks', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('quiz_id');
			$table->foreign('quiz_id')->references('id')->on('quizzes')->onDelete('cascade');
			$table->string('hint');
			$table->string('answer');
			$table->string('accepted_answer')->nullable()->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quiz_fill_in_the_blanks');
    }
}
