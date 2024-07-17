<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchPlayerVotingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_player_voting', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('match_id');
            $table->foreign('match_id')->references('id')->on('matches')->onDelete('cascade');
            $table->unsignedInteger('player_id');
            $table->foreign('player_id')->references('id')->on('players')->onDelete('cascade');
            $table->unsignedInteger('consumer_id')->nullable()->default(null);
            $table->foreign('consumer_id')->references('id')->on('consumers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('match_player_voting');
    }
}
