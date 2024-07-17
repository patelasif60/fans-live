<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasedLoyaltyRewardProductOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchased_loyalty_reward_product_options', function (Blueprint $table) {
			$table->unsignedInteger('purchased_loyalty_reward_product_id');
			$table->foreign('purchased_loyalty_reward_product_id', 'plrp_id_foreign')->references('id')->on('purchased_loyalty_reward_products')->onDelete('cascade');
			$table->unsignedInteger('loyalty_reward_option_id');
			$table->foreign('loyalty_reward_option_id', 'lro_id_foreign')->references('id')->on('loyalty_reward_options')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchased_loyalty_reward_product_options');
    }
}
