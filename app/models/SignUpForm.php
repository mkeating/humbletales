<?php

	class SignUpForm {

		//rules for validation
		private $rules = array(
			'name'		=>'required|unique:users',
			'email'		=>'required|email|unique:users',
			'password'	=>'required'
			);

		private $attributes;
		private $validation;

		public function __construct(array $attributes)
		{
			$attributes['password'] = Hash::make($attributes['password']);
			$this->attributes = $attributes;
		}

		public function isInvalid()
		{
			return ! $this->isValid();
		}
	
		public function isValid()
		{
			$this->validation = Validator::make($this->attributes, $this->rules);
			return $this->validation->passes();
		}

		public function getValidation()
		{
			return $this->validation;
		}
		
		public function createUser()
		{
			if ($this->isInvalid())
			{
				return false;
			}

		 	$user = new User($this->attributes);

		 	return $user;



		}
	}