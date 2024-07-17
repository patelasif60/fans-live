<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialOfferProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('special_offer_products', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('special_offer_id');
			$table->foreign('special_offer_id')->references('id')->on('special_offers')->onDelete('cascade');
			$table->unsignedInteger('product_id');
			$table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
			$table->float('discount_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('special_offer_products');
    }
}
