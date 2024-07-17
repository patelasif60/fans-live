<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsumerMembershipPackageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consumer_membership_package', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('club_id');
            $table->foreign('club_id')->references('id')->on('clubs')->onDelete('cascade');
            $table->string('transaction_reference_id');
            $table->string('psp_reference_id')->nullable()->default(null);
            $table->string('payment_method')->nullable()->default(null);
            $table->string('status_code')->nullable()->default(null);
            $table->string('psp')->nullable()->default(null);
            $table->string('psp_account')->nullable()->default(null);
            $table->unsignedInteger('membership_package_id')->nullable()->default(null);
            $table->foreign('membership_package_id')->references('id')->on('membership_packages')->onDelete('set null')->onUpdate('cascade');
            $table->unsignedInteger('consumer_id');
            $table->foreign('consumer_id')->references('id')->on('consumers')->onDelete('cascade');
            $table->string('receipt_number')->nullable()->default(null);
            $table->string('payment_brand')->nullable()->default(null);
            $table->integer('duration');
            $table->string('vat_rate');
            $table->float('price', 8, 2);
            $table->float('fee', 8, 2)->default(0.00);
            $table->enum('currency', ['EUR', 'GBP'])->nullable()->default(null);
            $table->enum('status', ['successful','failed','pending','unresolved','unprocessed'])->nullable()->default(null);
            $table->enum('payment_status', ['Paid', 'Unpaid'])->default('Unpaid');
            $table->string('result_description')->nullable()->default(null);
            $table->json('card_details')->nullable()->default(null);
            $table->json('custom_parameters')->nullable()->default(null);
            $table->datetime('transaction_timestamp')->nullable()->default(null);
            $table->tinyInteger('is_active')->default(0);
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
        Schema::dropIfExists('consumer_membership_package');
    }
}
