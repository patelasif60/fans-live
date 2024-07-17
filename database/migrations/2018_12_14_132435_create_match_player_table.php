<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchPlayerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_player', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('player_id');
            $table->foreign('player_id')->references('id')->on('players')->onDelete('cascade');
            $table->unsignedInteger('match_id');
            $table->foreign('match_id')->references('id')->on('matches')->onDelete('cascade');
            $table->unsignedInteger('club_id')->nullable()->default(null);
            $table->foreign('club_id')->references('id')->on('clubs')->onDelete('set null');
            $table->enum('type', ['lineup', 'bench']);
            $table->string('position')->nullable()->default(null);
            $table->integer('shirt_number')->nullable()->default(null);
            $table->boolean('is_substitute');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('match_player');
    }
}
