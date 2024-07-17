<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembershipPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('membership_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('club_id')->nullable()->default(null);
            $table->foreign('club_id')->references('id')->on('clubs')->onDelete('cascade');
            $table->string('title');
            $table->text('benefits')->nullable()->default(null);
            $table->integer('membership_duration')->nullable()->default(null);
            $table->string('rewards_percentage_override')->nullable()->default(null);
            $table->float('price', 8, 2);
            $table->float('vat_rate', 8, 2);
            $table->string('icon')->nullable()->default(null);
            $table->string('icon_file_name')->nullable()->default(null);
            $table->enum('status', ['Hidden', 'Published']);
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
        Schema::dropIfExists('membership_packages');
    }
}
