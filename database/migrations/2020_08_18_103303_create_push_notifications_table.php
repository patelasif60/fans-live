<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePushNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('club_id')->nullable()->default(null);
            $table->foreign('club_id')->references('id')->on('clubs')->onDelete('cascade');
            $table->string('title');
            $table->text('message');
            $table->enum('swipe_action_category', array_keys(config('fanslive.SWIPE_ACTION_CATEGORIES')));
            $table->unsignedInteger('swipe_action_item')->nullable();
            $table->datetime('publication_date');
            $table->integer('send_to_user_attending_this_match')->nullable()->default(null);
            $table->boolean('is_notification_sent')->default(0);
            $table->integer('created_by')->unsigned()->nullable()->default(null);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->integer('updated_by')->unsigned()->nullable()->default(null);
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
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
        Schema::dropIfExists('push_notifications');
    }
}
