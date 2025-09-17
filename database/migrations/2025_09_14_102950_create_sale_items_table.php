<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSaleItemsTable extends Migration {

	public function up()
	{
		Schema::create('sale_items', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('sale_id')->unsigned();
			$table->integer('item_id')->unsigned();
			$table->decimal('quantity', 10,2);
			$table->decimal('unit_price', 10,2);
			$table->decimal('total_price', 10,2);
		});
	}

	public function down()
	{
		Schema::drop('sale_items');
	}
}