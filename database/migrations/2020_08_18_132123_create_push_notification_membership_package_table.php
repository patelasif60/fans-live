<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePushNotificationMembershipPackageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_notification_membership_package', function (Blueprint $table) {
            $table->unsignedInteger('push_notification_id');
            $table->foreign('push_notification_id', 'pn_pnmp_id_foreign')->references('id')->on('push_notifications')->onDelete('cascade');
            $table->unsignedInteger('membership_package_id');
            $table->foreign('membership_package_id', 'mp_pnmp_id_foreign')->references('id')->on('membership_packages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('push_notification_membership_package');
    }
}
