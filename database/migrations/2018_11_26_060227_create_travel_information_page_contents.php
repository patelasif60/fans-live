<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTravelInformationPageContents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travel_information_page_contents', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('travel_information_page_id')->default(null);
            $table->foreign('travel_information_page_id', 'tipc_travel_information_page_id_foreign')->references('id')->on('travel_information_pages')->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->integer('display_order')->nullable()->default(null);
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
        Schema::dropIfExists('travel_information_page_contents');
    }
}
