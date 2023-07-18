<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCostsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('costs', function(Blueprint $table)
		{
			$table->id();
			$table->float('value', 10, 0)->nullable();
			$table->string('currency', 45)->nullable(true);
			$table->boolean('allow_part_pay')->default(0);
			$table->text('description')->nullable(true);
			$table->bigInteger('plan_id')->nullable()->unsigned()->index();
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
		Schema::drop('costs');
	}

}
