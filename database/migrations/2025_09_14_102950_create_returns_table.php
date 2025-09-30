<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReturnsTable extends Migration {

	public function up()
	{
		Schema::create('returns', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->bigInteger('sale_id')->unsigned();
			$table->bigInteger('safe_id')->unsigned();
			$table->bigInteger('user_Id')->unsigned();
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