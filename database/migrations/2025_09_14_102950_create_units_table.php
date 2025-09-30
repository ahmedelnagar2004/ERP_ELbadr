<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUnitsTable extends Migration {

	public function up()
	{
		Schema::create('units', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name');
			$table->tinyInteger('status');
		});
	}

	public function down()
	{
		Schema::drop('units');
	}
}