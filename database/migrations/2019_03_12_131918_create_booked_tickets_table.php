<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookedTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booked_tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ticket_transaction_id');
            $table->foreign('ticket_transaction_id')->references('id')->on('ticket_transactions')->onDelete('cascade');
            $table->unsignedInteger('stadium_block_seat_id')->nullable();
            $table->foreign('stadium_block_seat_id')->references('id')->on('stadium_block_seats')->onDelete('cascade');
            $table->unsignedInteger('seat')->nullable();
            $table->unsignedInteger('pricing_band_id')->nullable();
            $table->foreign('pricing_band_id')->references('id')->on('pricing_bands')->onDelete('set null');
            $table->float('price', 8, 2);
            $table->float('vat_rate', 8, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booked_tickets');
    }
}
