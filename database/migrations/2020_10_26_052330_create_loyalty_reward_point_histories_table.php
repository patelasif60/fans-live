<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoyaltyRewardPointHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loyalty_reward_point_histories', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('consumer_id');
			$table->foreign('consumer_id')->references('id')->on('consumers')->onDelete('cascade');
			$table->unsignedInteger('transaction_id')->nullable()->default(null);
			$table->enum('transaction_type', ['event', 'food_and_drink', 'merchandise', 'membership', 'hospitality', 'ticket', 'loyalty_reward'])->nullable()->default(null);
			$table->integer('points')->nullable()->default(null);
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
        Schema::dropIfExists('loyalty_reward_point_histories');
    }
}
