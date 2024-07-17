<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHospitalitySuites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hospitality_suites', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->float('price', 8, 2);
            $table->float('vat_rate', 8, 2);
            $table->unsignedInteger('club_id')->default(null)->nullable();
            $table->text('short_description')->nullable()->default(null);
            $table->text('long_description')->nullable()->default(null);
            $table->string('image')->nullable()->default(null);
            $table->string('image_file_name')->nullable()->default(null);
            $table->tinyInteger('number_of_seat');
            $table->tinyInteger('is_active');
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
        Schema::dropIfExists('hospitality_suites');
    }
}
