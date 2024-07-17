<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('special_offers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('club_id');
			$table->string('title');
			$table->enum('type', ['food_and_drink', 'merchandise']);
			$table->string('image')->nullable()->default(NULL);
			$table->string('image_file_name')->nullable()->default(NULL);
            $table->enum('status', ['Published', 'Hidden','Archived']);
			$table->tinyInteger('is_restricted_to_over_age')->default(0);
			$table->enum('discount_type', ['fixed_amount', 'percentage']);
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
        Schema::dropIfExists('special_offers');
    }
}
