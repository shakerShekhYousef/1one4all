<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('requests', function(Blueprint $table)
		{
			$table->id();
			$table->text('body')->nullable();
			$table->string('request_type', 45)->nullable(true);
			$table->unsignedBigInteger('trainer_id');
            $table->unsignedBigInteger('player_id');
			$table->timestamps();
			$table->foreign('trainer_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('player_id')
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
		Schema::drop('requests');
	}

}
