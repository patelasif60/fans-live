<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductAndLoyaltyRewardTransactionCollections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_and_loyalty_reward_transaction_collections', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('staff_id')->nullable()->default(NULL);
            $table->foreign('staff_id', 'palrtc_s_staff_id')->references('id')->on('staff')->onDelete('cascade');
            $table->unsignedInteger('transaction_id');
			$table->enum('type', ['product','loyalty_reward'])->default(NULL);
            $table->enum('status', ['New','Preparing','Ready','Collected'])->default('New');
            $table->datetime('collected_time')->nullable()->default(NULL);
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_and_loyalty_reward_transaction_collections');
    }
}
