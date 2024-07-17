<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsumerCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consumer_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('consumer_id')->nullable()->default(null);
            $table->foreign('consumer_id')->references('id')->on('consumers')->onDelete('cascade');
            $table->string('token');
            $table->string('brand');
            $table->string('truncated_pan');
            $table->string('postcode')->nullable()->default(null);
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
        Schema::dropIfExists('consumer_cards');
    }
}
