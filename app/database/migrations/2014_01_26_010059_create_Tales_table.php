<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTalesTable extends Migration {

	public function up()
	{
		Schema::create('Tales', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('title', 255);
			$table->integer('current_section')->default('1');
			$table->boolean('complete');
		});
	}

	public function down()
	{
		Schema::drop('Tales');
	}
}