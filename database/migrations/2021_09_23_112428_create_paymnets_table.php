<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymnetsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('paymnets', function(Blueprint $table)
		{
			$table->id();
			$table->text('description')->nullable(true);
			$table->float('amount', 10, 0)->nullable();
			$table->string('currency', 45)->nullable(true);
			$table->bigInteger('created_by')->nullable();
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
		Schema::drop('paymnets');
	}

}
