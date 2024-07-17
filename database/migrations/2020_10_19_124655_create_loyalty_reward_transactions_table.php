<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoyaltyRewardTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loyalty_reward_transactions', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('match_id');
			$table->foreign('match_id')->references('id')->on('matches')->onDelete('cascade');
			$table->unsignedInteger('club_id');
			$table->foreign('club_id')->references('id')->on('clubs')->onDelete('cascade');
			$table->unsignedInteger('consumer_id');
			$table->string('receipt_number')->nullable()->default(null);
			$table->unsignedInteger('points');
			$table->enum('selected_collection_time', ['as_soon_as_possible', 'half_time', 'full_time']);
			$table->dateTime('collection_time');
			$table->unsignedInteger('collection_point_id')->nullable();
			$table->foreign('collection_point_id')->references('id')->on('collection_points')->onDelete('cascade');
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
        Schema::dropIfExists('loyalty_reward_transactions');
    }
}
