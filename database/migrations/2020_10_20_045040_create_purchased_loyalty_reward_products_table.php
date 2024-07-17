<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasedLoyaltyRewardProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchased_loyalty_reward_products', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('loyalty_reward_transaction_id');
			$table->foreign('loyalty_reward_transaction_id', 'lrt_id_foreign')->references('id')->on('loyalty_reward_transactions')->onDelete('cascade');
			$table->unsignedInteger('loyalty_reward_id');
			$table->foreign('loyalty_reward_id')->references('id')->on('loyalty_rewards')->onDelete('cascade');
			$table->integer('quantity');
			$table->integer('per_quantity_points')->nullable()->default(NULL);
			$table->integer('per_quantity_additional_options_point')->nullable()->default(NULL);
			$table->integer('total_points')->nullable()->default(NULL);
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchased_loyalty_reward_products');
    }
}
