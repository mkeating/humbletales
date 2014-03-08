<?php

class TaleTableSeeder extends Seeder {

	public function run()
	{
		//DB::table('Tales')->delete();

		// initial
		Tale::create(array(
				'title' => 'seed',
				'complete' => FALSE
			));
	}
}