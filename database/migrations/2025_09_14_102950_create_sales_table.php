<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSalesTable extends Migration {

	public function up()
	{
		Schema::create('sales', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('client_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->integer('safe_id')->unsigned();
			$table->decimal('total', 10,2);
			$table->decimal('discount', 10,2);
			$table->tinyInteger('discount_type');
			$table->decimal('shipping_cost', 10,2);
			$table->decimal('net_amount', 10,2);
			$table->decimal('paid_amount', 10,2);
			$table->decimal('remaining_amount', 10,2);
			$table->string('invoice_number');
			$table->tinyInteger('payment_type');
		});
	}

	public function down()
	{
		Schema::drop('sales');
	}
}