<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionPointStadiumBlockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collection_point_stadium_block', function (Blueprint $table) {
            $table->unsignedInteger('collection_point_id');
            $table->foreign('collection_point_id')->references('id')->on('collection_points')->onDelete('cascade');
            $table->unsignedInteger('stadium_block_id');
            $table->foreign('stadium_block_id')->references('id')->on('stadium_blocks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collection_point_stadium_block');
    }
}
