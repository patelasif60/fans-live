<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizzesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quizzes', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('club_id');
			$table->foreign('club_id')->references('id')->on('clubs')->onDelete('cascade');
			$table->string('title');
			$table->text('description');
			$table->string('image')->nullable()->default(null);
			$table->string('image_file_name')->nullable()->default(null);
			$table->enum('status', ['Hidden', 'Published']);
			$table->enum('type', ['multiple_choice', 'fill_in_the_blanks']);
			$table->datetime('publication_date');
			$table->integer('time_limit')->unsigned()->nullable()->default(NULL);
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
        Schema::dropIfExists('quizzes');
    }
}
