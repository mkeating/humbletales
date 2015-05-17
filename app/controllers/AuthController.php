<?php

class AuthController extends BaseController{

	public function showSignup()
	{
		return View::make('auth/signup');
	}

	public function postSignup()
	{
		//get inputs
		$userdata = Input::except('_token');

		$form = new SignUpForm($userdata);

		if ($form->isInvalid())
		{
			return Redirect::to('/')->withErrors($form->getValidation())->withInput(Input::except('password'));
		}

		$user = $form->createUser();

		$user->save();

		if(Auth::loginUsingID($user->id))
		{
			return Redirect::to('/');
		}
		else{
			echo 'woops';
		}
	
	}

	public function showReferral($id)
	{
		return View::make('auth/referral', array('id'=> $id));

	}

	public function postReferral()
	{
		//get inputs
		$userdata = Input::except('_token');
		$userdata['current_tale'] = intval($userdata['current_tale']);
		$current_tale = $userdata['current_tale'];
		

		//error checking for current_tale not existing, or if another user is already assigned to it
		$already_assigned = DB::table('users')
								->where('current_tale', $current_tale)
								->get();

		$tale_exists = DB::table('tales')
						->where('id', $current_tale)
						->get();
		
		if(/*$already_assigned or */!$tale_exists)
		{	
			//TODO: make a view
			echo 'not a valid referral';
		}
		else{

			$form = new SignUpForm($userdata);

			if ($form->isInvalid()){
				return Redirect::to('/')->withErrors($form->getValidation())->withInput(Input::except('password'));
			}

			$user = $form->createUser();

			$user->save();

			if(Auth::loginUsingID($user->id))
			{
				return Redirect::to('/');
			}
			else{
				echo 'woops';
			}

		}
		
	}

	public function showLogin()
	{
		//check if already logged in
		if(Auth::check())
		{
			//redirect
			return Redirect::to('/');
		}

		//show the login page
		return View::make('auth/login');
	}

	public function postLogin()
	{
		//get inputs
		$userdata = Input::except('_token');

		//declare rules for validation
		$rules = array(
			'email'		=> 'required|email',
			'password'	=> 'required'
			);

		//validate inputs
		$validator = Validator::make($userdata, $rules);
		
		if($validator->passes())
		{
			//try to log user in
			if (Auth::attempt($userdata))
			{
				//redirect
				return Redirect::to('/');
			}
			else
			{
				//redirect back to login
				return Redirect::to('login')->with('message', 'email/password incorrect')->withInput(Input::except('password'));
			}
		}
		//validation error
		return Redirect::to('login')->withErrors($validator)->withInput(Input::except('password'));
	}

	public function getLogout()
	{
		Auth::logout();

		return Redirect::to('/');
	}
}