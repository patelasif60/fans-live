<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchHospitalitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_hospitalities', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('match_id');
            $table->foreign('match_id')->references('id')->on('matches')->onDelete('cascade');
            $table->float('rewards_percentage_override', 8, 2)->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('match_hospitalities');
    }
}
