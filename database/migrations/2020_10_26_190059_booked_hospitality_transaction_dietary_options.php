<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BookedHospitalityTransactionDietaryOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booked_hospitality_suite_transaction_dietary_options', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('hospitality_suite_transaction_id');
            $table->foreign('hospitality_suite_transaction_id', 'bhtdo_hst_id_foreign')->references('id')->on('hospitality_suite_transactions')->onDelete('cascade');
            $table->unsignedInteger('hospitality_suite_dietary_option_id');
            $table->foreign('hospitality_suite_dietary_option_id', 'bhtdo_hsdo_id_foreign')->references('id')->on('hospitality_suite_dietary_options')->onDelete('cascade');
            $table->unsignedInteger('quantity')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booked_hospitality_suite_transaction_dietary_options');
    }
}
