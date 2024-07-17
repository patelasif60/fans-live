<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('api_id')->nullable()->default(null);
            $table->unsignedInteger('competition_id');
            $table->foreign('competition_id')->references('id')->on('competitions');
            $table->enum('status', ['scheduled ', 'live', 'in_play', 'paused', 'finished', 'postponed', 'suspended', 'cancelled','awarded']);
            $table->integer('minute')->nullable()->default(null);
            $table->integer('attendance')->nullable()->default(null);
            $table->string('venue')->nullable()->default(null);
            $table->string('stage')->nullable()->default(null);
            $table->integer('matchday')->nullable()->default(null);
            $table->string('group')->nullable()->default(null);
            $table->datetime('last_updated')->nullable();
            $table->datetime('kickoff_time');
            $table->datetime('match_endtime')->nullable();
            $table->boolean('is_match_imported');
            $table->unsignedInteger('winner')->nullable()->default(null);
            $table->foreign('winner')->references('id')->on('clubs')->onDelete('set null');
            $table->string('duration')->nullable()->default(null);
            $table->integer('full_time_home_team_score')->nullable()->default(null);
            $table->integer('full_time_away_team_score')->nullable()->default(null);
            $table->integer('half_time_home_team_score')->nullable()->default(null);
            $table->integer('half_time_away_team_score')->nullable()->default(null);
            $table->integer('extra_time_home_team_score')->nullable()->default(null);
            $table->integer('extra_time_away_team_score')->nullable()->default(null);
            $table->integer('penalties_home_team_score')->nullable()->default(null);
            $table->integer('penalties_away_team_score')->nullable()->default(null);
            $table->unsignedInteger('home_team_id')->nullable()->default(null);
            $table->foreign('home_team_id')->references('id')->on('clubs')->onDelete('set null');
            $table->unsignedInteger('away_team_id')->nullable()->default(null);
            $table->foreign('away_team_id')->references('id')->on('clubs')->onDelete('set null');
            $table->jsonb('referees')->nullable()->default(null);
            $table->boolean('is_published');
            $table->boolean('is_ticket_sale_enabled');
            $table->boolean('is_hospitality_ticket_sale_enabled');
            $table->integer('created_by')->unsigned()->nullable()->default(null);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->integer('updated_by')->unsigned()->nullable()->default(null);
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
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
        Schema::dropIfExists('matches');
    }
}
