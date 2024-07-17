<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ctas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('club_id');
            $table->foreign('club_id')->references('id')->on('clubs')->onDelete('cascade');
            $table->string('title');
            $table->string('image')->nullable()->default(null);
            $table->string('image_file_name')->nullable()->default(null);
            $table->string('button1_text');
            $table->string('button1_action');
            $table->string('button1_item')->nullable()->default(null);
            $table->string('button2_text')->nullable()->default(null);
            $table->string('button2_action')->nullable()->default(null);
            $table->string('button2_item')->nullable()->default(null);
            $table->enum('status', ['Hidden', 'Published']);
            $table->datetime('publication_date');
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
        Schema::dropIfExists('ctas');
    }
}
