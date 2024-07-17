<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchTicketingPricingBandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_ticketing_pricing_bands', function (Blueprint $table) {
            $table->unsignedInteger('match_ticketing_id');
            $table->foreign('match_ticketing_id')->references('id')->on('match_ticketings')->onDelete('cascade');
            $table->unsignedInteger('pricing_band_id');
            $table->foreign('pricing_band_id')->references('id')->on('pricing_bands')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('match_ticketing_pricing_bands');
    }
}
