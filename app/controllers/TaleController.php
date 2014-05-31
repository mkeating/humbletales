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
			//currently assumes next user already exists
			$rules = array(
				'title'		=>'Required',
				'content'	=>'Required',
				'emailNext'	=>'Required'
				);

			//validate inputs
			$validator = Validator::make($taledata, $rules);
			

			if($validator->passes())
				{
					

					//find next user
					$next_user = DB::table('users')->where('email', $taledata['emailNext'])->first();

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

						//insert story chunk
						DB::table('users_tales')->insert(
							array(	'user_id'	=> Auth::user()->id,
									'tale_id'	=> $new_tale_id,
									'content'	=> $taledata['content'],
									'section'	=> 1 )
							);

						//send email to next user

						$data = array(
							'email'		=> $next_user->email,
							'name1'		=> $next_user->name,
							'name2'		=> Auth::user()->name,
							'id'		=> $new_tale_id
						); 

						Mail::send('emails.pass', $data, function($message) use ($data)
							{
								
							    $message->to($data['email'])->subject($data['name2'].' wants to write with you!');
							});
						
					}
					else{
						//user doesnt exist

						//create the new tale and get its ID
						$new_tale_id = DB::table('tales')
							->insertGetId(array(
								'title'				=> $taledata['title'],
								'current_section'	=> 2)
								
								);

						//insert story chunk
						DB::table('users_tales')->insert(
							array(	'user_id'	=> Auth::user()->id,
									'tale_id'	=> $new_tale_id,
									'content'	=> $taledata['content'],
									'section'	=> 1 )
							);

						//send referral email
						$data = array(
							'email'		=> $taledata['emailNext'],
							'name2'		=> Auth::user()->name,
							'ref_email'	=> Auth::user()->email,
							'id'		=> $new_tale_id
						); 

						Mail::send('emails.referral', $data, function($message) use ($data)
							{
								
							    $message->to($data['email'])->subject($data['name2'].' wants to write with you!');
							});

					}						

					//success
					return Redirect::to('');
				}
			//validation error
			return Redirect::to('new')->withErrors($validator)->withInput();
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

		//determine if story is at the last section
		if ($last_section == 3)
		{
			//get inputs
			$taledata = array(
				'content'	=> Input::get('content'),
			);


			//rules for validation
			//currently assumes next user already exists
			$rules = array(
				'content'	=>'Required',
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
							'section'	=> $last_section + 1 )
					);

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
		else{

			//get inputs
			$taledata = array(
				'content'	=> Input::get('content'),
				'emailNext'	=> Input::get('emailNext')
			);


			//rules for validation
			//currently assumes next user already exists
			$rules = array(
				'content'	=>'Required',
				'emailNext'	=>'Required'
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
							'section'	=> $last_section + 1 )
					);

				//update the tale's current section
				DB::table('tales')
					->where('id', $current_tale_id)
					->update(array('current_section'=> $last_section + 1));


				//find next user
				$next_user = DB::table('users')->where('email', $taledata['emailNext'])->first();

				//set next user's current tale to this one
				DB::table('users')
					->where('email', $taledata['emailNext'])
					->update(array('current_tale' => $current_tale_id));

				//set this user's current tale to NULL
				DB::table('users')
					->where('id', Auth::user()->id)
					->update(array('current_tale'=> NULL));


				//send email to next user
				if($next_user)
				{
						$data = array(
							'email'		=> $next_user->email,
							'name1'		=> $next_user->name,
							'name2'		=> Auth::user()->name
						);

						print_r($next_user);

						Mail::send('emails.pass', $data, function($message) use ($data)
							{
							    $message->to($data['email'])->subject($data['name2'].' wants to write with you!');
							});
				}
				else{

					/* NOT YET IMPLEMENTED
					//user doesnt exist

						//insert story chunk
						DB::table('users_tales')->insert(
							array(	'user_id'	=> Auth::user()->id,
									'tale_id'	=> $current_tale_id,
									'content'	=> $taledata['content'],
									'section'	=> 1 )
							);

						//send referral email
						$data = array(
							'email'		=> $taledata['emailNext'],
							'name2'		=> Auth::user()->name,
							'ref_email'	=> Auth::user()->email,
							'id'		=> $current_tale_id
						); 

						Mail::send('emails.referral', $data, function($message) use ($data)
							{
								
							    $message->to($data['email'])->subject($data['name2'].' wants to write with you!');
							});
							*/
					echo "user doesnt exist";

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

	public function refusal($id){

		//get the tale sections with the id in question
		$current_tale = DB::table('users_tales')
								->where('tale_id', $id)
								->orderBy('section', 'asc')
								->get();

		//print_r($current_tale);

		//find last user in this tale
		$last_user_id = end($current_tale)->user_id;

		$last_user = DB::table('users')
							->where('id', $last_user_id)
							->first();

		print_r($last_user);

		//set last user's current tale to the refused tale, so it appears on their homepage again


		//send an email to last user, telling them to log in and pick someone else
		
		







	}


}