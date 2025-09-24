<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration {

	public function up()
	{
		Schema::create('files', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('filename');
			$table->string('path');
			$table->string('ext');
			$table->integer('size');
			$table->string('mime_type');
			$table->string('usage');
			$table->nullableMorphs('fileable');
		});
	}

	public function down()
	{
		Schema::drop('files');
	}
}