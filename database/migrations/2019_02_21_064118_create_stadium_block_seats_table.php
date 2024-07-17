<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStadiumBlockSeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stadium_block_seats', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('stadium_block_id');
            $table->foreign('stadium_block_id')->references('id')->on('stadium_blocks')->onDelete('cascade');
            $table->string('row', 50);
            $table->unsignedInteger('seat');
            $table->enum('type', ['Seat', 'Stairwell', 'Disabled'])->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stadium_block_seats');
    }
}
