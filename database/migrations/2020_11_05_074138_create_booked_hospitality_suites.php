<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookedHospitalitySuites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booked_hospitality_suites', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('hospitality_suite_transaction_id');
            $table->foreign('hospitality_suite_transaction_id','hst_id')->references('id')->on('hospitality_suite_transactions')->onDelete('cascade');
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
        Schema::dropIfExists('booked_hospitality_suites');
    }
}
