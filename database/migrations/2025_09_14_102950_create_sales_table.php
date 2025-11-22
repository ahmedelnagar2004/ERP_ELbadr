<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSalesTable extends Migration {

	public function up()
	{
		Schema::create('sales', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->bigInteger('client_id')->unsigned();
			$table->bigInteger('user_id')->unsigned();
			$table->bigInteger('safe_id')->unsigned();
			$table->decimal('total', 10,2);
			$table->decimal('discount', 10,2);
			$table->decimal('shipping_cost', 10,2);
			$table->decimal('net_amount', 10,2);
			$table->enum('discount_type', ['fixed', 'percentage'])->default('fixed');
			$table->string('invoice_number');
			$table->tinyInteger('type');
			$table->tinyInteger('payment_type');
		});
	}

	public function down()
	{
		Schema::drop('sales');
	}
}