<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('plans', function(Blueprint $table)
		{
			$table->id();
			$table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('trainer_id')->nullable();
			$table->timestamps();
			$table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
			$table->foreign('trainer_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('plans');
	}

}
