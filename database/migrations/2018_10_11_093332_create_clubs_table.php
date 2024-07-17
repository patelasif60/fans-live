<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clubs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->integer('club_category_id')->nullable();
            $table->string('logo')->nullable()->default(null);
            $table->string('logo_file_name')->nullable()->default(null);
            $table->enum('status', ['Hidden', 'Published']);
            $table->string('external_app_id')->nullable()->default(null);
            $table->string('primary_colour')->nullable()->default(null);
            $table->string('secondary_colour')->nullable()->default(null);
            $table->string('time_zone', 100)->nullable()->default(null);
            $table->string('currency')->nullable();
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
        Schema::dropIfExists('clubs');
    }
}
