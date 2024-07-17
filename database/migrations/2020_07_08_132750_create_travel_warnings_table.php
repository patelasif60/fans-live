<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTravelWarningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travel_warnings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('club_id');
            $table->foreign('club_id')->references('id')->on('clubs')->onDelete('cascade');
            $table->text('text')->nullable()->default(NULL);
            $table->enum('status', ['Hidden', 'Published']);
            $table->datetime('publication_date_time')->nullable();
            $table->datetime('show_until')->nullable();
            $table->enum('color', ['red', 'green', 'amber']);
            $table->integer('created_by')->unsigned()->nullable()->default(NULL);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->integer('updated_by')->unsigned()->nullable()->default(NULL);
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
        Schema::dropIfExists('travel_warnings');
    }
}
