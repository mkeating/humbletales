<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	public function up()
	{
		Schema::create('users', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name', 255);
			$table->string('email', 255)->unique();
			$table->string('password', 255);
			$table->integer('current_tale')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('users');
	}
}