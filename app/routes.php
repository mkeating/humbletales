<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'HomeController@showHome');

// Auth routes

	//Sign up
Route::get('signup', 'AuthController@showSignup');
Route::post('signup', 'AuthController@postSignup');

	//login
Route::get('login', array('as'=> 'login', 'uses'=> 'AuthController@showLogin'));
Route::post('login', 'AuthController@postLogin');

	//logout
Route::get('logout', array('as'=> 'logout', 'uses'=> 'AuthController@getLogout'));

	///refer
Route::get('referral/{id}', array('as'=> 'referral', 'uses'=> 'AuthController@showReferral'));
Route::post('referral', array('as'=> 'referral', 'uses'=> 'AuthController@postReferral'));


// Tale routes
//Route::get('new', 'TaleController@showNew');
Route::post('new', 'TaleController@postNew');

//Route::get('continue', 'TaleController@showContinue');
Route::post('continue', 'TaleController@postContinue');

Route::get('refusal/{id}/{secret}', array('as'=> 'refusal', 'uses'=> 'TaleController@refusal'));

// Library routes
Route::get('library', 'LibraryController@showLibrary');
Route::get('library/{id}', array('as' => 'library', 'uses' => 'LibraryController@showTale'));

// Secure Routes
Route::group(array('before' => 'auth'), function()
{
	Route::get('secret', 'HomeController@showSecret');
});