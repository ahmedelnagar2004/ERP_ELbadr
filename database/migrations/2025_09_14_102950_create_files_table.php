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
			$table->string('usage');
			$table->string('path');
			$table->string('ext')->nullable();
			$table->string('filename')->nullable();
			$table->unsignedBigInteger('size')->nullable();
			$table->string('mime_type')->nullable();
			$table->nullableMorphs('fileable');
		});
	}

	public function down()
	{
		Schema::drop('files');
	}
}