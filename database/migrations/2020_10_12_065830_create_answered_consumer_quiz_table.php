<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnsweredConsumerQuizTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answered_consumer_quiz', function (Blueprint $table) {
            $table->unsignedInteger('consumer_id');
            $table->foreign('consumer_id')->references('id')->on('consumers')->onDelete('cascade');
            $table->unsignedInteger('quiz_id');
            $table->foreign('quiz_id')->references('id')->on('quizzes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answered_consumer_quiz');
    }
}
