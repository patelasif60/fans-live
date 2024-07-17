<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feed_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('club_id');
            $table->foreign('club_id')->references('id')->on('clubs')->onDelete('cascade');
            $table->unsignedInteger('content_feed_id')->default(null);
            $table->foreign('content_feed_id')->references('id')->on('content_feeds')->onDelete('cascade');
            $table->string('title')->nullable()->default(null);
            $table->text('text')->nullable()->default(null);
            $table->enum('status', ['Hidden', 'Published']);
            $table->jsonb('media')->nullable()->default(null);
            $table->string('youtube_id')->nullable()->default(null);
            $table->string('feed_url')->nullable()->default(null);
            $table->datetime('publication_date');
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
        Schema::dropIfExists('feed_items');
    }
}
