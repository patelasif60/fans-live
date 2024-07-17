<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookedTicketScanStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booked_ticket_scan_status', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('staff_id');
			$table->foreign('staff_id')->references('id')->on('staff')->onDelete('cascade');
            $table->unsignedInteger('ticket_id')->nullable()->default(null);
            $table->enum('type',['Hospitality','Event','Match']);
            $table->dateTime('scan_datetime')->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booked_ticket_scan_statuses');
    }
}
