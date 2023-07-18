<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPlanPaymentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('plan_payment', function(Blueprint $table)
		{
			$table->foreign('payment_id')->references('id')->on('paymnets')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('plan_id')->references('id')->on('plans')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('plan_payment', function(Blueprint $table)
		{
			$table->dropForeign('payment_id_id_fk');
			$table->dropForeign('plan_payment_plan_id_fk');
		});
	}

}
