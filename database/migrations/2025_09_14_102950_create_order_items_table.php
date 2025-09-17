<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderItemsTable extends Migration {

	public function up()
	{
		Schema::create('order_items', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('order_id')->unsigned();
			$table->integer('item_id')->unsigned();
			$table->decimal('unit_price', 10,2);
			$table->decimal('quantity');
			$table->decimal('total_price', 10,2);
		});
	}

	public function down()
	{
		Schema::drop('order_items');
	}
}