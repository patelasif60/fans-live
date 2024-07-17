<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePushNotificationHistoryConsumerStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_notification_history_consumer_status', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('push_notification_history_id');
            $table->foreign('push_notification_history_id','pnh_id')->references('id')->on('push_notification_history')->onDelete('cascade');
            $table->unsignedInteger('consumer_id');
            $table->foreign('consumer_id')->references('id')->on('consumers')->onDelete('cascade');
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('push_notification_history_consumer_status');
    }
}
