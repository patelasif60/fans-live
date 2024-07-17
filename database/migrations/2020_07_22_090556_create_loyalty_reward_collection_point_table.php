<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoyaltyRewardCollectionPointTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loyalty_reward_collection_points', function (Blueprint $table) {
            $table->unsignedInteger('loyalty_reward_id');
            $table->foreign('loyalty_reward_id')->references('id')->on('loyalty_rewards')->onDelete('cascade');
            $table->unsignedInteger('collection_point_id');
            $table->foreign('collection_point_id')->references('id')->on('collection_points')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loyalty_reward_collection_points');
    }
}
