<?php

class DatabaseSeeder extends Seeder {

	public function run()
	{
		Eloquent::unguard();

		$this->call('TaleTableSeeder');
		$this->command->info('Tale table seeded!');
	}
}