<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoyaltyRewardOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loyalty_reward_options', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('loyalty_reward_id');
            $table->foreign('loyalty_reward_id')->references('id')->on('loyalty_rewards')->onDelete('cascade');
            $table->string('name');
            $table->unsignedInteger('additional_point')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loyalty_reward_options');
    }
}
