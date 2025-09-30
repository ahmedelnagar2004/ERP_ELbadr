<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWarehousesTable extends Migration {

	public function up()
	{
		Schema::create('warehouses', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name');
			$table->text('description')->nullable();
			$table->tinyInteger('status');
		});
	}

	public function down()
	{
		Schema::drop('warehouses');
	}
}