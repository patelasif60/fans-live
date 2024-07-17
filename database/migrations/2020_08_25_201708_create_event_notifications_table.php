<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_notifications', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('consumer_id');
			$table->foreign('consumer_id')->references('id')->on('consumers')->onDelete('cascade');
			$table->unsignedInteger('club_id');
			$table->foreign('club_id')->references('id')->on('clubs')->onDelete('cascade');
            $table->unsignedInteger('event_id');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
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
        Schema::dropIfExists('event_notifications');
    }
}
