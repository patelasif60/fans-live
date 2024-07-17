<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStadiumGeneralSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stadium_general_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('club_id');
            $table->foreign('club_id')->references('id')->on('clubs')->onDelete('cascade');
            $table->string('name');
            $table->string('address');
            $table->string('address_2')->nullable();
            $table->string('town');
            $table->string('postcode');
            $table->string('latitude')->nullable()->default(null);
            $table->string('longitude')->nullable()->default(null);
            $table->string('aerial_view_ticketing_graphic')->nullable()->default(null);
            $table->string('aerial_view_ticketing_graphic_file_name')->nullable()->default(null);
            $table->string('image')->nullable()->default(null);
            $table->string('image_file_name')->nullable()->default(null);
            $table->boolean('is_using_allocated_seating')->nullable()->default(false);
            $table->integer('number_of_seats')->unsigned()->nullable();
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
        Schema::dropIfExists('stadium_general_settings');
    }
}
