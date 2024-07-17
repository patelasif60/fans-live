<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ClubBankDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('club_bank_details', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('club_id');
			$table->foreign('club_id')->references('id')->on('clubs')->onDelete('cascade');
			$table->string('bank_name');
			$table->string('account_name');
			$table->unsignedBigInteger('account_number');
			$table->unsignedInteger('sort_code');
			$table->string('bic')->nullable()->default(null);
			$table->string('iban')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('club_bank_details');
    }
}
