<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_events', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('match_id');
            $table->foreign('match_id')->references('id')->on('matches')->onDelete('cascade');
            $table->unsignedInteger('club_id')->nullable()->default(null);
            $table->foreign('club_id')->references('id')->on('clubs')->onDelete('set null');
            $table->unsignedInteger('player_id');
            $table->foreign('player_id')->references('id')->on('players')->onDelete('cascade');
            $table->enum('event_type', ['goal', 'red_card', 'yellow_card', 'yellow_red_card', 'substitution', 'half_time', 'full_time']);
            $table->integer('minute');
            $table->integer('extra_time')->nullable();
            $table->string('action_replay_video')->nullable()->default(null);
            $table->string('action_replay_video_file_name')->nullable()->default(null);
            $table->unsignedInteger('substitute_player_id')->nullable()->default(null);
            $table->foreign('substitute_player_id')->references('id')->on('players')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('match_events');
    }
}
