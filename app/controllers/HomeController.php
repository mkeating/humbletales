<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/


	public function showHome()
	{

		//if logged in user
		if(Auth::check())
		{

			
			//get this user's current tale
			$current_tale_id = Auth::user()->current_tale;
			
			//if user has a current tale (showContinue)
			if($current_tale_id != NULL){

					$title		 = DB::table('tales')
								->where('id', $current_tale_id)
								->first();

					$current_tale = DB::table('users_tales')
											->where('tale_id', $current_tale_id)
											->orderBy('section', 'asc')
											->get();
					$content = '';

					foreach($current_tale as $value){
						$content =  $content.$value->content;
					}

					
					$last_section = end($current_tale)->section;
					$greeting = "Hi, ".Auth::user()->name.". You have a story to work on!";

					return View::make('home/home', array('title'=> $title->title, 'content'=> $content, 'section'=> $last_section, 'greeting'=> $greeting));
				}				

			//if user has no current tale (showNew)
			else
			{
				$greeting = "Hi, ".Auth::user()->name;
				return View::make('home/home', array('greeting'=> $greeting));
			}


		}
		//no logged in user
		else
		{
			return View::make('home/home');
		}

		return View::make('home/home');
	}

	public function showSecret()
	{
		return View::make('secret');
	}

}