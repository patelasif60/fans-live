<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStandingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('standings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('competition_id');
            $table->foreign('competition_id')->references('id')->on('competitions');
            $table->string('stage');
            $table->string('type');
            $table->string('group')->nullable();
            $table->integer('position');
            $table->unsignedInteger('club_id')->nullable()->default(null);
            $table->foreign('club_id')->references('id')->on('clubs')->onDelete('set null');
            $table->integer('played_games');
            $table->integer('won');
            $table->integer('draw');
            $table->integer('lost');
            $table->integer('points');
            $table->integer('goal_for');
            $table->integer('goal_against');
            $table->integer('goal_difference');
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
        Schema::dropIfExists('standings');
    }
}
