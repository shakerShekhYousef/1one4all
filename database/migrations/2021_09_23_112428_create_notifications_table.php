<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notifications', function(Blueprint $table)
		{
			$table->id();
            $table->string('title', 45)->nullable();
			$table->string('notification_type', 45)->nullable();
			$table->text('text')->nullable();
			$table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('sender_id')->nullable();
			$table->unsignedBigInteger('request_id')->nullable();
			$table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('sender_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
			$table->foreign('request_id')
                ->references('id')
                ->on('requests');
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
		Schema::drop('notifications');
	}

}
