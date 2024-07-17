<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HospitalityDietaryOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('hospitality_suite_dietary_options', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('hospitality_suite_id');
            $table->foreign('hospitality_suite_id')->references('id')->on('hospitality_suites')->onDelete('cascade');
            $table->string('option_name')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hospitality_suite_dietary_options');
    }
}
