<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SellMatchTicket extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sell_match_ticket', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('booked_ticket_id');
            $table->foreign('booked_ticket_id')->references('id')->on('booked_tickets')->onDelete('cascade');
            $table->enum('return_time_to_wallet',['72_hours_before', '48_hours_before','24_hours_before','12_hours_before']);
            $table->string('account_number');
            $table->string('sort_code');
            $table->boolean('is_sold')->default(false);
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('sell_match_ticket');
    }
}
