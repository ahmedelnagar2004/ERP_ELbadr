<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSafesTable extends Migration {

	public function up()
	{
		Schema::create('safes', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name');
			$table->tinyInteger('type');
			$table->decimal('balance', 12,2);
			$table->tinyInteger('status');
			$table->text('description')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('safes');
	}
}