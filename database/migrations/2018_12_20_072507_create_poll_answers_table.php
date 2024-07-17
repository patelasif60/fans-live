<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePollAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('poll_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('poll_id');
            $table->foreign('poll_id')->references('id')->on('polls')->onDelete('cascade');
            $table->unsignedInteger('consumer_id');
            $table->foreign('consumer_id')->references('id')->on('consumers')->onDelete('cascade');
            $table->unsignedInteger('poll_option_id')->nullable();
            $table->foreign('poll_option_id')->references('id')->on('poll_options')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('poll_answers');
    }
}
