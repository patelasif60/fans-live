<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizEndTextsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_end_texts', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('quiz_id');
			$table->foreign('quiz_id')->references('id')->on('quizzes')->onDelete('cascade');
			$table->string('end_text');
			$table->integer('points_threshold');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quiz_end_texts');
    }
}
