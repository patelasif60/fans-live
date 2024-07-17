<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchHospitalityHospitalitySuitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_hospitality_hospitality_suites', function (Blueprint $table) {
            $table->unsignedInteger('match_hospitality_id');
            $table->foreign('match_hospitality_id', 'mhid_foreign')->references('id')->on('match_hospitalities')->onDelete('cascade');
            $table->unsignedInteger('hospitality_suite_id');
            $table->foreign('hospitality_suite_id', 'hsid_foreign')->references('id')->on('hospitality_suites')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('match_hospitality_hospitality_suites');
    }
}
