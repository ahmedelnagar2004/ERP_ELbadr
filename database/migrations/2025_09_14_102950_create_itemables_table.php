<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateItemablesTable extends Migration {

	public function up()
	{
		Schema::create('itemables', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->morphs('itemable');
			$table->bigInteger('item_id');
			$table->decimal('quantity');
			$table->decimal('unit_price', 10,2);
			$table->decimal('total_price', 10,2);
		});
	}

	public function down()
	{
		Schema::drop('itemables');
	}
}