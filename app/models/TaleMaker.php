<?php

	class TaleMaker {

		//rules for validation
		private $rules = array(
			'title'		=>'required',
			'emailNext'	=>'required|email'
			);

		private $attributes;
		private $validation;

		public function __construct(array $attributes)
		{
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

		public function createTale()
		{
			if ($this->isInvalid())
			{
				return false;
			}

		 	$tale = new Tale($this->attributes);
		 	$tale->current_section = 2;

		 	return $tale;



		}
		
	}