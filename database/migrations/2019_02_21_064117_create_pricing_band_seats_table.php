<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricingBandSeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pricing_band_seats', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('stadium_block_id');
            $table->foreign('stadium_block_id')->references('id')->on('stadium_blocks')->onDelete('cascade');
            $table->unsignedInteger('pricing_band_id');
            $table->foreign('pricing_band_id')->references('id')->on('pricing_bands')->onDelete('cascade');
            $table->string('row');
            $table->integer('seat_from');
            $table->integer('seat_to');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pricing_band_seats');
    }
}