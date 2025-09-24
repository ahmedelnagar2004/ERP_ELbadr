<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateItemsTable extends Migration {

	public function up()
	{
		Schema::create('items', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->string('name');
			$table->string('item_code')->nullable();
			$table->text('description')->nullable();
			$table->decimal('price', 10,2);
			$table->decimal('quantity');
			$table->bigInteger('category_id')->unsigned();
			$table->bigInteger('unit_id')->unsigned();
			$table->boolean('is_shown_in_store');
			$table->decimal('minimum_stock');
		});
	}

	public function down()
	{
		Schema::drop('items');
	}
}