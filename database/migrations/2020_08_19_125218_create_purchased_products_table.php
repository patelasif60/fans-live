<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchased_products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_transaction_id');
            $table->foreign('product_transaction_id')->references('id')->on('product_transactions')->onDelete('cascade');
            $table->unsignedInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unsignedIntegerstring('special_offer_id')->nullable();
            $table->foreign('special_offer_id')->references('id')->on('special_offers')->onDelete('cascade');
            $table->string('special_offer_discount_type')->nullable();
            $table->float('special_offer_discount', 8, 2)->nullable();
            $table->unsignedInteger('quantity')->nullable();
            $table->float('vat_rate', 8, 2);
            $table->float('per_quantity_price', 8, 2);
            $table->float('per_quantity_actual_price', 8, 2)->nullable();
            $table->float('per_quantity_additional_options_cost', 8, 2);
            $table->float('total_price', 8, 2);
            $table->datetime('transaction_timestamp')->nullable()->default(null);
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
        Schema::dropIfExists('purchased_products');
    }
}
