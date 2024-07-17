<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClubOpeningTimeSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('club_opening_time_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('club_id')->nullable()->default(null);
            $table->foreign('club_id')->references('id')->on('clubs')->onDelete('cascade');
            $table->integer('food_and_drink_minutes_open_before_kickoff');
            $table->integer('food_and_drink_minutes_closed_after_fulltime');
            $table->integer('merchandise_minutes_open_before_kickoff');
            $table->integer('merchandise_minutes_closed_after_fulltime');
            $table->integer('loyalty_rewards_minutes_open_before_kickoff');
            $table->integer('loyalty_rewards_minutes_closed_after_fulltime');
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
        Schema::dropIfExists('club_opening_time_settings');
    }
}
