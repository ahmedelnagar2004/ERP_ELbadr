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
<<<<<<< HEAD
			$table->tinyInteger('type')->default(1);
			$table->decimal('balance', 12, 2)->default(0);
			$table->string('currency', 10)->default('EGP');
			$table->boolean('status')->default(true);
			$table->text('description')->nullable();
			$table->unsignedBigInteger('branch_id')->nullable();
			$table->string('account_number')->nullable();
=======
			$table->tinyInteger('type');
			$table->decimal('balance', 12,2);
			$table->tinyInteger('status');
			$table->text('description')->nullable();
>>>>>>> 24b0991d0ec959d4099cb3f5625b19e7db8f06b4
		});
	}

	public function down()
	{
		Schema::drop('safes');
	}
}