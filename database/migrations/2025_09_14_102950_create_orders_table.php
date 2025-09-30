<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersTable extends Migration {

	public function up()
	{
		Schema::create('orders', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->bigInteger('client_id')->unsigned();
			$table->tinyInteger('status');
			$table->tinyInteger('payment_method');
			$table->decimal('price', 10,2);
			$table->decimal('shipping_cost', 10,2);
			$table->decimal('total_price', 10,2);
			$table->bigInteger('sale_id')->unsigned()->nullable();
		});
	}

	public function down()
	{
		Schema::drop('orders');
	}
}