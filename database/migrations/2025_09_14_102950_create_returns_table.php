<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReturnsTable extends Migration {

	public function up()
	{
		Schema::create('returns', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('sale_id')->unsigned();
			$table->integer('safe_id')->unsigned();
			$table->integer('user_Id')->unsigned();
			$table->string('return_number')->nullable();
			$table->decimal('return_amount', 10,2);
			$table->text('reason')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('returns');
	}
}