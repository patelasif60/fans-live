<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTravelOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travel_offers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('club_id');
            $table->foreign('club_id')->references('id')->on('clubs')->onDelete('cascade');
            $table->string('title');
            $table->text('content')->nullable()->default(null);
            $table->string('thumbnail')->nullable()->default(null);
            $table->string('thumbnail_file_name')->nullable()->default(null);
            $table->string('banner')->nullable()->default(null);
            $table->string('banner_file_name')->nullable()->default(null);
            $table->string('icon')->nullable()->default(null);
            $table->string('icon_file_name')->nullable()->default(null);
            $table->string('button_colour');
            $table->string('button_text_colour');
            $table->string('button_text');
            $table->string('button_url');
            $table->enum('status', ['Hidden', 'Published']);
            $table->datetime('publication_date');
            $table->datetime('show_until');
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
        Schema::dropIfExists('travel_offers');
    }
}
