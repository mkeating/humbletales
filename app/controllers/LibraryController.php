<?php

class LibraryController extends BaseController{

	public function showLibrary()
	{
		//get all tales
		$tales = DB::table('tales')
					->where('current_section', 4)
					->get();
		//print_r($tales);
		return View::make('library/library', array('tales' => $tales));
	}

	public function showTale($id)
	{
		$title = DB::table('tales')
			->where('id', $id)
			->select('title')->first();

		$tale = DB::table('users_tales')
			->where('tale_id', $id)
			->orderBy('section', 'asc')->get();

		$content = '';
		$authors = '';
		$count = 1;

		foreach($tale as $value)
		{
			$content = $content.'<span class="auth'.$count.'">'.$value->content.'</span>';
			
			$user = DB::table('users')
				->where('id', $value->user_id)
				->first();
			$authors = $authors.'<span class="auth'.$count.'">'.$user->name.'</span><br>';	
			$count++;
		}

		return View::make('library/tale', 
					array(
						'title'		=> $title->title,
						'content'	=> $content,
						'author'	=> $authors)
					);
	}


}