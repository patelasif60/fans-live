<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchTicketNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_ticket_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('consumer_id');
            $table->foreign('consumer_id')->references('id')->on('consumers')->onDelete('cascade');
            $table->unsignedInteger('club_id');
            $table->foreign('club_id')->references('id')->on('clubs')->onDelete('cascade');
            $table->unsignedInteger('match_id');
            $table->foreign('match_id')->references('id')->on('matches')->onDelete('cascade');
            $table->unsignedInteger('stadium_block_id')->nullable()->default(null);
            $table->foreign('stadium_block_id')->references('id')->on('stadium_blocks')->onDelete('cascade');
            $table->enum('reason', ['sold_out', 'unavailable']);
            $table->tinyInteger('is_notified')->default(0);
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
        Schema::dropIfExists('match_ticket_notifications');
    }
}
