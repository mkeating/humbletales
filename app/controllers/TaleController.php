<?php

class TaleController extends BaseController{

	/* ////////////// TO DO //////////////

	 1) prevent assigning story to occupied user
	 2) implement story queue (eliminates need for #1)
	 3) implement feedback messages throughout ("Your story has been sent!", etc)
	 4) general refactoring

	 ////////////////////////////////////*/

		public function postNew()
		{
			//get inputs
			$taledata = Input::except('_token');

			$talemaker = new TaleMaker($taledata);

			if ($talemaker->isInvalid())
			{
				return Redirect::to('/')->withErrors($validator)->withInput();
			}

			$tale = $talemaker->createTale();
			$tale->save();
			$new_tale_id = $tale->id;
			
			//find next user
			$next_user = DB::table('users')->where('email', $taledata['emailNext'])->first();

			//make a secret to validate refusals
			$secret = str_random(11);

			$chunkdata = array(	  	
									'user_id'	=> Auth::user()->id,
									'tale_id'	=> $new_tale_id,
									'content'	=> $taledata['content'],
									'section'	=> 1,
									'secret'	=> $secret
								);

			$chunkmaker = new ChunkMaker($chunkdata);

			if ($chunkmaker->isInvalid())
			{
				return Redirect::to('/')->withErrors($validator)->withInput();
			}

			$chunk = $chunkmaker->createChunk();
			//insert story chunk
			$chunk->save();

			//user exists
			if($next_user){

				//set next user's current tale to this one
				DB::table('users')
							->where('id', $next_user->id)
							->update(array('current_tale' => $new_tale_id));


				// REFUSAL STATES //
						
				//post-refusal submission
				if (Auth::user()->been_refused == 1)
					{
						//reset been_refused
						DB::table('users')
								->where('id', Auth::user()->id)
								->update(array('been_refused'=> NULL));	
					}

				//send email to next user
				$data = array(
							'email'		=> $next_user->email,
							'name1'		=> $next_user->name,
							'name2'		=> Auth::user()->name,
							'id'		=> $new_tale_id,
							'secret'	=> $secret
						); 

						Mail::send('emails.pass', $data, function($message) use ($data)
							{
								
							    $message->to($data['email'])->subject($data['name2'].' wants to write with you!');
							});
						
			}

			//user doesnt exist
			else{
											
				// REFUSAL STATES //

				//post-refusal submission
				if (Auth::user()->been_refused == 1)
				{
					//reset been_refused
					DB::table('users')
								->where('id', Auth::user()->id)
								->update(array('been_refused'=> NULL));
				}

				//send referral email
				$data = array(
							'email'		=> $taledata['emailNext'],
							'name2'		=> Auth::user()->name,
							'ref_email'	=> Auth::user()->email,
							'id'		=> $new_tale_id,
							'secret'	=> $secret
						); 

				Mail::send('emails.referral', $data, function($message) use ($data)
							{
								
							    $message->to($data['email'])->subject($data['name2'].' wants to write with you!');
							});

			}						

			//success
			return Redirect::to('/');
			
		}


	public function postContinue()
	{

		//get this user's current tale
			$current_tale_id = Auth::user()->current_tale;
			$current_tale = DB::table('users_tales')
									->where('tale_id', $current_tale_id)
									->orderBy('section', 'asc')
									->get();
			$title = DB::table('tales')
							->where('id', $current_tale_id)
							->select('title')
							->first();
			$last_section = end($current_tale)->section;

		//determine if story is at the final section
		if ($last_section == 3)
		{
			//get inputs
			$taledata = array(
				'content'	=> Input::get('content'),
			);

			$secret = str_random(11);

			//rules for validation
			$rules = array(
				//'content'	=>'Required',
			);

			//validate inputs
			$validator = Validator::make($taledata, $rules);

			if($validator->passes())
			{

				//insert story chunk
				DB::table('users_tales')->insert(
					array(	'user_id'	=> Auth::user()->id,
							'tale_id'	=> $current_tale_id,
							'content'	=> $taledata['content'],
							'section'	=> $last_section + 1 ,
							'secret'	=> $secret
						));

				//remove secret from last section
				DB::table('users_tales')
					->where('section', $last_section)
					->update(array('secret'=> NULL));

				//update the tale's current section
				DB::table('tales')
					->where('id', $current_tale_id)
					->update(array('current_section'=> $last_section + 1));

				//set this user's current tale to NULL
				DB::table('users')
					->where('id', Auth::user()->id)
					->update(array('current_tale'=> NULL));

				//send email to all authors to notify Tale is done

				$all_ids = DB::table('users_tales')
							->where('tale_id', $current_tale_id)
							->select('user_id')->get();
				
				$addresses = array();
				foreach ($all_ids as $value) {
					
					$email = DB::table('users')
								->where('id', $value->user_id)
								->select('email')->first();
					
					array_push($addresses, $email->email);

				}
				
				foreach($addresses as $value){
					echo $value;
					$data = array(
							'email'		=> $value,
							'title'		=> $title->title,
							'id'		=> $current_tale_id
						); 
					Mail::send('emails.done', $data, function($message) use ($data)
							{
							    $message->to($data['email'])->subject($data['title'].' is finished!');
							});
				}

				//success
				return Redirect::to('/');

			}
			else{

				//validation error
				return Redirect::to('/')->withErrors($validator)->withInput();

			}
		}

		// if this is not the last section
		else{

			//get inputs
			$taledata = array(
				'content'	=> Input::get('content'),
				'emailNext'	=> Input::get('emailNext')
			);

			$secret = str_random(11);

			//rules for validation
			
			$rules = array(
				//'content'	=>'Required',
				'emailNext'	=>'required|email'
			);

			//validate inputs
			$validator = Validator::make($taledata, $rules);

			if($validator->passes())
			{
				//skip this if its a post-refusal submission
				if (Auth::user()->been_refused != 1)
					{
						//not a refusal
						// REFUSAL STATES //

						//insert story chunk
						DB::table('users_tales')->insert(
							array(	'user_id'	=> Auth::user()->id,
									'tale_id'	=> $current_tale_id,
									'content'	=> $taledata['content'],
									'section'	=> $last_section + 1 ,
									'secret'	=> $secret
							));

						//remove secret from last section
							DB::table('users_tales')
								->where('section', $last_section)
								->update(array('secret'=> NULL));


							//update the tale's current section
							DB::table('tales')
								->where('id', $current_tale_id)
								->update(array('current_section'=> $last_section + 1));
					}
						else{

							//a refusal
							//reset been_refused
							$chunk_id = DB::table('users_tales')
											->where('tale_id', $current_tale_id)
											->where('section', $last_section)
											->select('user_tale_id')	
											->first();

						
							DB::table('users')
								->where('id', Auth::user()->id)
								->update(array('been_refused'=> NULL));


							//update this story chunk with a new secret
							DB::table('users_tales')
										->where('user_tale_id', $chunk_id->user_tale_id)
										->update(array(	'secret'=> $secret));
					}


				//find next user
				$next_user = DB::table('users')->where('email', $taledata['emailNext'])->first();


				if($next_user)
				{

					//set next user's current tale to this one
					DB::table('users')
						->where('email', $taledata['emailNext'])
						->update(array('current_tale' => $current_tale_id));

					//set this user's current tale to NULL
					DB::table('users')
						->where('id', Auth::user()->id)
						->update(array('current_tale'=> NULL));


					//send email to next user
				
						$data = array(
							'email'		=> $next_user->email,
							'name1'		=> $next_user->name,
							'name2'		=> Auth::user()->name,
							'id'		=> $current_tale_id,
							'secret'	=> $secret
						);

						Mail::send('emails.pass', $data, function($message) use ($data)
							{
							    $message->to($data['email'])->subject($data['name2'].' wants to write with you!');
							});
				}
				else{
					//user doesnt exist

					//set this user's current tale to NULL
					DB::table('users')
						->where('id', Auth::user()->id)
						->update(array('current_tale'=> NULL));

					//send referral email
						$data = array(
							'email'		=> $taledata['emailNext'],
							'name2'		=> Auth::user()->name,
							'ref_email'	=> Auth::user()->email,
							'id'		=> $current_tale_id,
							'secret'	=> $secret
						); 

						Mail::send('emails.referral', $data, function($message) use ($data)
							{
								
							    $message->to($data['email'])->subject($data['name2'].' wants to write with you!');
							});
				}
				//success
				return Redirect::to('/');
			}
			else{
				//validation error
				return Redirect::to('/')->withErrors($validator)->withInput();
			}
			
		}

	}

	public function refusal($id, $secret){
		
		//get the tale sections with the id in question
		$current_tale = DB::table('users_tales')
								->where('tale_id', $id)
								->orderBy('section', 'asc')
								->get();

		$title = DB::table('tales')
							->where('id', $id)
							->first();						

		// this is a legit refusal link
		if(end($current_tale)->secret == $secret){


			echo("match");
			
			
			//find last user in this tale
				$last_user_id = end($current_tale)->user_id;

				

				$last_user = DB::table('users')
									->where('id', $last_user_id)
									->first();

			//(if existing user) find who has this tale as their current, and set it to NULL again so it won't show on their homepage
			DB::table('users')
				->where('current_tale', $id)
				->update(array('current_tale' => NULL));

			//set the original writer's current tale back to this one, so it shows up on their home page again, 
				//and set their refused to true (prevents their homepage from allowing them to enter more story content on refusal)
			DB::table('users')
				->where('id', $last_user_id)
				->update(array('current_tale'=> $id,
								'been_refused'=> 1));


			//email that user, telling them to log back in and pick someone else

				$data = array(
							'email'		=> $last_user->email,
							'name1'		=> $last_user->name,
							'title'		=> $title->title,
						);

						Mail::send('emails.refused', $data, function($message) use ($data)
							{
							    $message->to($data['email'])->subject("Someone didn't want to write with you...");
							});

			//redirect the refusing user to a thanks page		
			return View::make('auth/thanks');
		}

		//not a legit refusal link

		//TODO: make a view for this
		
		else{
			print_r( end($current_tale)->secret);
			echo("<br>");
			print_r( $secret);
			echo("<br>");
			print_r($current_tale);
			echo("<br>");
			echo "woops";
		}	
	

	}


}