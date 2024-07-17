<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasedProductOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchased_product_options', function (Blueprint $table) {
            $table->unsignedInteger('purchased_product_id');
            $table->foreign('purchased_product_id')->references('id')->on('purchased_products')->onDelete('cascade');
            $table->unsignedInteger('product_option_id');
            $table->foreign('product_option_id')->references('id')->on('product_options')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchased_product_options');
    }
}
