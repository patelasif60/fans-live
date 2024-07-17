<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialOfferMembershipPackageAvailabilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('special_offer_membership_package_availability', function (Blueprint $table) {
			$table->unsignedInteger('special_offer_id');
			$table->foreign('special_offer_id','so_id_foreign')->references('id')->on('special_offers')->onDelete('cascade');
			$table->unsignedInteger('membership_package_id');
			$table->foreign('membership_package_id','mp_id_foreign')->references('id')->on('membership_packages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('special_offer_membership_package_availability');
    }
}
