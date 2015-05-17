<?php

	class Chunk extends Eloquent {

		protected $table = 'users_tales';

		protected $fillable = array('user_id','tale_id','section','content','secret');
		
		
	}

