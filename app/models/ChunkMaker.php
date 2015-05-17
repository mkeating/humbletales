<?php

	class ChunkMaker {

		//rules for validation
		private $rules = array(
			'content'	=>'required',
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

		public function createChunk()
		{
			if ($this->isInvalid())
			{
				return false;
			}


		 	$chunk = new Chunk($this->attributes);

		 	return $chunk;



		}


		
	}