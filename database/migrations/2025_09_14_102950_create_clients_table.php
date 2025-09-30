<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClientsTable extends Migration {

	public function up()
	{
		Schema::create('clients', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->string('name');
			$table->string('email')->unique();
			$table->string('phone')->unique();
			$table->string('address');
			$table->decimal('balance', 12,2);
			$table->tinyInteger('status');
		});
	}

	public function down()
	{
		Schema::drop('clients');
	}
}