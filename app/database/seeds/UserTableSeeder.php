<?php

class UserTableSeeder extends Seeder {

	public function run()
	{
		//delete any existing users
		DB::table('users')->delete();

		User::create(array(
			'name'=>'keat',
			'email'=>'mkeating2225@gmail.com',
			'password'=>Hash::make('dragon'))
		);
	}
}