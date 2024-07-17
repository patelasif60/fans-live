<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStadiumBlockStadiumEntrancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stadium_block_stadium_entrance', function (Blueprint $table) {
            $table->unsignedInteger('stadium_block_id');
            $table->foreign('stadium_block_id')->references('id')->on('stadium_blocks')->onDelete('cascade');
            $table->unsignedInteger('stadium_entrance_id');
            $table->foreign('stadium_entrance_id')->references('id')->on('stadium_entrances')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stadium_block_stadium_entrance');
    }
}
