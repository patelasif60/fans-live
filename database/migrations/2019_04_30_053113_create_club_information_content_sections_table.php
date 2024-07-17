<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClubInformationContentSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('club_information_content_sections', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('club_information_page_id');
            $table->foreign('club_information_page_id','lq_id_foreign')->references('id')->on('club_information_pages')->onDelete('cascade');
            $table->string('title');
            $table->text('content')->nullable()->default(NULL);
            $table->tinyInteger('display_order');
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
        Schema::dropIfExists('club_information_content_sections');
    }
}
