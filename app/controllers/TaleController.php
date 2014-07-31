<?php

class TaleController extends BaseController{

	

		public function postNew()
		{
			//get inputs
			$taledata = array(
				'title'		=> Input::get('title'),
				'content'	=> Input::get('content'),
				'emailNext'	=> Input::get('emailNext')
			);

			//rules for validation
			$rules = array(
				'title'		=>'required',
				'emailNext'	=>'required|email'
				);

			//validate inputs
			$validator = Validator::make($taledata, $rules);
			
			if($validator->passes())
				{

					//find next user
					$next_user = DB::table('users')->where('email', $taledata['emailNext'])->first();

					$secret = substr(urlencode(Hash::make(str_random(24))), 0, 24);

					//user exists
					if($next_user){

						//create the new tale and get its ID
						$new_tale_id = DB::table('tales')
							->insertGetId(array(
								'title'				=> $taledata['title'],
								'current_section'	=> 2)
								);

						//set next user's current tale to this one
						DB::table('users')
							->where('email', $taledata['emailNext'])
							->update(array('current_tale' => $new_tale_id));


						//skip this if its a post-refusal submission
						if (Auth::user()->been_refused != 1)
						{


							//insert story chunk
							DB::table('users_tales')->insert(
								array(	'user_id'	=> Auth::user()->id,
										'tale_id'	=> $new_tale_id,
										'content'	=> $taledata['content'],
										'section'	=> 1,
										'secret'	=> $secret
									));
						}
						else{
							//reset been_refused
							DB::table('users')->update(
								array( 'been_refused'	=> 0
									));

							//insert story chunk
							DB::table('users_tales')->insert(
								array(	'user_id'	=> Auth::user()->id,
										'tale_id'	=> $new_tale_id,
										'content'	=> $taledata['content'],
										'section'	=> 1,
										'secret'	=> $secret
									));
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
						
						//create the new tale and get its ID
						$new_tale_id = DB::table('tales')
							->insertGetId(array(
								'title'				=> $taledata['title'],
								'current_section'	=> 2)
						);						

						//skip this if its a post-refusal submission
						if (Auth::user()->been_refused != 1)
						{
						//insert story chunk
						DB::table('users_tales')->insert(
							array(	'user_id'	=> Auth::user()->id,
									'tale_id'	=> $new_tale_id,
									'content'	=> $taledata['content'],
									'section'	=> 1 ,
									'secret'	=> $secret
							));
						}
						else{
							//reset been_refused
							DB::table('users')->update(
								array( 'been_refused'	=> 0
									));

							//insert story chunk
						DB::table('users_tales')->insert(
							array(	'user_id'	=> Auth::user()->id,
									'tale_id'	=> $new_tale_id,
									'content'	=> $taledata['content'],
									'section'	=> 1 ,
									'secret'	=> $secret
							));
						
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
			//validation error
			return Redirect::to('/')->withErrors($validator)->withInput();
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

			$secret = substr(urlencode(Hash::make(str_random(24))), 0, 24);

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

			$secret = substr(urlencode(Hash::make(str_random(24))), 0, 24);

			//rules for validation
			//currently assumes next user already exists
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
						//insert story chunk
						DB::table('users_tales')->insert(
							array(	'user_id'	=> Auth::user()->id,
									'tale_id'	=> $current_tale_id,
									'content'	=> $taledata['content'],
									'section'	=> $last_section + 1 ,
									'secret'	=> $secret
							));
					}
						else{
							//reset been_refused
							DB::table('users')->update(
								array( 'been_refused'	=> 0
									));

							//update this story chunk with a new secret
						DB::table('users_tales')
							->where(array('tale_id'		=>$current_tale_id,
											'section'	=> $last_section))
							->update(
							array(	'secret'	=> $secret
							));
					}

				//remove secret from last section
				DB::table('users_tales')
					->where('section', $last_section)
					->update(array('secret'=> NULL));


				//update the tale's current section
				DB::table('tales')
					->where('id', $current_tale_id)
					->update(array('current_section'=> $last_section + 1));


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

						print_r($next_user);

						Mail::send('emails.pass', $data, function($message) use ($data)
							{
							    $message->to($data['email'])->subject($data['name2'].' wants to write with you!');
							});
				}
				else{
					//user doesnt exist

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


		// this is a legit refusal link
		if(end($current_tale)->secret == urlencode($secret)){

			
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
						);

						Mail::send('emails.refused', $data, function($message) use ($data)
							{
							    $message->to($data['email'])->subject("Someone didn't want to write with you...");
							});

			//redirect the refusing user to a thanks page		
			return View::make('auth/thanks');
		}

		//dnot a legit refusal link
		else{
			print_r(end($current_tale)->secret);
			echo("<br>");
			print_r(urlencode($secret));
			echo("<br>");
			print_r($current_tale);
			echo("<br>");
			echo "woops";
		}	
		

	}


}