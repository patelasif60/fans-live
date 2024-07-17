<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookedEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booked_events', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('event_transaction_id');
            $table->foreign('event_transaction_id')->references('id')->on('event_transactions')->onDelete('cascade');
            $table->unsignedInteger('seat');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booked_events');
    }
}
