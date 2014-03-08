<?php

class AuthController extends BaseController{

	public function showSignup()
	{
		return View::make('auth/signup');
	}

	public function postSignup()
	{

		//get inputs
		$userdata = array(
			'name'		=> Input::get('name'),
			'email'		=> Input::get('email'),
			'password'	=> Input::get('password')
		);

		//rules for validation
		$rules = array(
			'name'		=>'Required',
			'email'		=>'Required',
			'password'	=>'Required'
			);

		//validate inputs
		$validator = Validator::make($userdata, $rules);

		if($validator->passes())
		{
			//sign user up
			User::create(array(
				'name'			=> $userdata['name'],
				'email'			=> $userdata['email'],
				'password'		=> Hash::make($userdata['password']),
				));

			//login the new user
			//unset($userdata['name']);

			if (Auth::attempt($userdata))
			{
				//redirect
				return Redirect::to('secret')->with('message','you have logged in');
			}
			else
			{
				//redirect back to login
				return Redirect::to('login')
					->withErrors(array('email'=>'wrong email','password'=>'wrong password'))
					->withInput(Input::except('password'));
			}


		}
		//validation error
		return Redirect::to('signup')->withErrors($validator)->withInput(Input::except('password'));
	}

	public function showReferral($id)
	{
		return View::make('auth/referral', array('id'=> $id));

	}

	public function postReferral()
	{
		//get inputs
		$userdata = array(
			'name'			=> Input::get('name'),
			'email'			=> Input::get('email'),
			'password'		=> Input::get('password'),
			'current_tale'	=> Input::get('next_id')
		);

		//rules for validation
		$rules = array(
			'name'		=>'Required',
			'email'		=>'Required',
			'password'	=>'Required',
			'next_id'	=>'Required'
			);

		//validate inputs
		$validator = Validator::make($userdata, $rules);

		if($validator->passes())
		{
			//sign user up
			User::create(array(
				'name'			=> $userdata['name'],
				'email'			=> $userdata['email'],
				'password'		=> Hash::make($userdata['password']),
				'current_tale'	=> $userdata['current_tale']
				));

			//login the new user
			//unset($userdata['name']);

			if (Auth::attempt($userdata))
			{
				//redirect
				return Redirect::to('secret')->with('message','you have logged in');
			}
			else
			{
				//redirect back to login
				return Redirect::to('login')
					->withErrors(array('email'=>'wrong email','password'=>'wrong password'))
					->withInput(Input::except('password'));
			}

		}
		//validation error
		return Redirect::to('signup')->withErrors($validator)->withInput(Input::except('password'));
	}



	public function showLogin()
	{
		//check if already logged in
		if(Auth::check())
		{
			//redirect
			return Redirect::to('secret');
		}

		//show the login page
		return View::make('auth/login');
	}

	public function postLogin()
	{
		//get inputs

		$userdata = array(
			'email'		=> Input::get('email'),
			'password'	=> Input::get('password')
			);

		//declare rules for validation
		$rules = array(
			'email'		=> 'Required',
			'password'	=> 'Required'
			);

		//validate inputs
		$validator = Validator::make($userdata, $rules);
		
		if($validator->passes())
		{
			//try to log user in
			if (Auth::attempt($userdata))
			{
				//redirect
				return Redirect::to('')->with('message','you have logged in');
			}
			else
			{
				//redirect back to login
				return Redirect::to('login')
					->withErrors(array('email'=>'wrong email','password'=>'wrong password'))
					->withInput(Input::except('password'));
			}
		}
		//validation error
		return Redirect::to('login')->withErrors($validator)->withInput(Input::except('password'));

	}

	public function getLogout()
	{
		Auth::logout();

		return Redirect::to('login');
	}
}