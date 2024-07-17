<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchTicketingMembershipPackage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_ticketing_membership_package', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('match_id');
            $table->foreign('match_id')->references('id')->on('matches')->onDelete('cascade');
            $table->unsignedInteger('membership_package_id');
            $table->foreign('membership_package_id')->references('id')->on('membership_packages')->onDelete('cascade');
            $table->datetime('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('match_ticketing_membership_package');
    }
}
