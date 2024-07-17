<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchTicketingAvailableBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_ticketing_available_blocks', function (Blueprint $table) {
            $table->unsignedInteger('match_ticketing_id');
            $table->foreign('match_ticketing_id')->references('id')->on('match_ticketings')->onDelete('cascade');
            $table->unsignedInteger('block_id');
            $table->foreign('block_id')->references('id')->on('stadium_blocks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('match_ticketing_available_blocks');
    }
}
