<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReturnItemsTable extends Migration {

	public function up()
	{
		Schema::create('return_items', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->bigInteger('return_id')->unsigned();
			$table->bigInteger('item_id')->unsigned();
			$table->decimal('quantity');
			$table->decimal('unit_price', 10,2);
			$table->decimal('total_price', 10,2);
		});
	}

	public function down()
	{
		Schema::drop('return_items');
	}
}