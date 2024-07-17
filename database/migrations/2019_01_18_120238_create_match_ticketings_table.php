<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchTicketingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_ticketings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('match_id');
            $table->foreign('match_id')->references('id')->on('matches')->onDelete('cascade');
            $table->integer('maximum_ticket_per_user');
            $table->string('unavailable_seats')->nullable()->default(null);
            $table->string('unavailable_seats_file_name')->nullable()->default(null);
            $table->float('rewards_percentage_override', 8, 2)->nullable()->default(null);
            $table->boolean('allow_ticket_returns_resales');
            $table->enum('ticket_resale_fee_type', ['fixed_fee', 'percentage_of_face_value'])->nullable();
            $table->float('ticket_resale_fee_amount', 8, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('match_ticketings');
    }
}
