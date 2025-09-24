<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSafeTransactionsTable extends Migration {

	public function up()
	{
		Schema::create('safe_transactions', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->bigInteger('safe_id')->unsigned();
			$table->bigInteger('user_id')->unsigned();
			$table->tinyInteger('type');
			$table->decimal('amount', 10,2);
			$table->nullableMorphs('reference');
			$table->text('description');
			$table->decimal('balance_after', 10,2);
		});
	}

	public function down()
	{
		Schema::drop('safe_transactions');
	}
}