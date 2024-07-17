<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchTicketingSponsorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_ticketing_sponsors', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('match_ticketing_id');
            $table->foreign('match_ticketing_id')->references('id')->on('match_ticketings')->onDelete('cascade');
            $table->string('logo')->nullable()->default(null);
            $table->string('logo_file_name')->nullable()->default(null);
            $table->integer('order')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('match_ticketing_sponsors');
    }
}
