<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('transaction_reference_id');
            $table->string('psp_reference_id')->nullable()->default(null);
            $table->string('payment_method')->nullable()->default(null);
            $table->string('status_code')->nullable()->default(null);
            $table->string('psp')->nullable()->default(null);
            $table->string('psp_account')->nullable()->default(null);
            $table->unsignedInteger('match_id');
            $table->foreign('match_id')->references('id')->on('matches')->onDelete('cascade');
            $table->unsignedInteger('club_id');
            $table->foreign('club_id')->references('id')->on('clubs')->onDelete('cascade');
            $table->unsignedInteger('consumer_id')->nullable()->default(null);
            $table->foreign('consumer_id')->references('id')->on('consumers')->onDelete('cascade');
            $table->string('receipt_number')->nullable()->default(null);
            $table->string('payment_brand')->nullable()->default(null);
            $table->float('price', 8, 2);
            $table->float('fee', 8, 2)->default(0.00);
            $table->string('currency');
            $table->enum('status', ['successful','failed','pending','unresolved','unprocessed'])->nullable()->default(null);
            $table->enum('payment_status', ['Paid', 'Unpaid'])->default('Unpaid');
            $table->string('result_description')->nullable()->default(null);
            $table->json('card_details')->nullable()->default(null);
            $table->json('custom_parameters')->nullable()->default(null);
            $table->datetime('transaction_timestamp')->nullable()->default(null);
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
        Schema::dropIfExists('ticket_transactions');
    }
}
