<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHospitalityUnavailableSeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hospitality_unavailable_seats', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('match_hospitality_id');
            $table->foreign('match_hospitality_id')->references('id')->on('match_hospitalities')->onDelete('cascade');
            $table->unsignedInteger('stadium_block_id');
            $table->foreign('stadium_block_id')->references('id')->on('stadium_blocks')->onDelete('cascade');
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
        Schema::dropIfExists('hospitality_unavailable_seats');
    }
}
