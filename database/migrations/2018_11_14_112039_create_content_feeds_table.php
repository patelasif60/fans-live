<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentFeedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_feeds', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('club_id');
            $table->foreign('club_id')->references('id')->on('clubs')->onDelete('cascade');
            $table->enum('type', ['Twitter', 'Facebook', 'Youtube', 'Instagram', 'RSS']);
            $table->string('name');
            $table->string('screen_name')->nullable()->default(null);
            $table->string('api_app_id')->nullable()->default(null);
            $table->string('api_key')->nullable()->default(null);
            $table->string('api_secret_key')->nullable()->default(null);
            $table->string('api_token')->nullable()->default(null);
            $table->string('api_token_secret_key')->nullable()->default(null);
            $table->string('api_channel_id')->nullable()->default(null);
            $table->string('rss_url')->nullable()->default(null);
            $table->string('last_inserted_data')->nullable()->default(null);
            $table->boolean('is_automatically_publish_items')->default(false);
            $table->datetime('last_imported')->nullable()->default(null);
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
        Schema::dropIfExists('content_feeds');
    }
}
