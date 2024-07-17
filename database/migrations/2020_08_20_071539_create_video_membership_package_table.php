<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoMembershipPackageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_membership_package', function (Blueprint $table) {
            $table->unsignedInteger('video_id');
            $table->foreign('video_id')->references('id')->on('videos')->onDelete('cascade');
            $table->unsignedInteger('membership_package_id');
            $table->foreign('membership_package_id')->references('id')->on('membership_packages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('video_membership_package');
    }
}
