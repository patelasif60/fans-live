<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePushNotificationHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_notification_history', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('push_notification_id');
            $table->foreign('push_notification_id')->references('id')->on('push_notifications')->onDelete('cascade');
            $table->integer('number_of_success')->default(0);
            $table->integer('number_of_failure')->default(0);
            $table->integer('number_of_modifications')->default(0);
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
        Schema::dropIfExists('push_notification_history');
    }
}
