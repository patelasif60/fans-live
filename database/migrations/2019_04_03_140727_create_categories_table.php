<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('club_id');
            $table->foreign('club_id')->references('id')->on('clubs')->onDelete('cascade');
            $table->string('title');
            $table->enum('type', ['food_and_drink', 'merchandise']);
            $table->string('image')->nullable()->default(null);
            $table->string('image_file_name')->nullable()->default(null);
            $table->float('rewards_percentage_override', 8, 2)->nullable()->default(null);
            $table->enum('status', ['Hidden', 'Published']);
            $table->tinyInteger('is_restricted_to_over_age')->default(0);
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
        Schema::dropIfExists('categories');
    }
}
