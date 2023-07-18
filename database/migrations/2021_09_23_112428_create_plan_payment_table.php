<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanPaymentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('plan_payment', function(Blueprint $table)
		{
			$table->id();
			$table->bigInteger('payment_id')->nullable()->unsigned()->index();
			$table->bigInteger('plan_id')->unsigned()->nullable()->index();
			$table->string('payment_type', 45)->nullable(true);
			$table->string('plan_payment_status', 45)->nullable(true);
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
		Schema::drop('plan_payment');
	}

}
