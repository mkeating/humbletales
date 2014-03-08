@extends('layouts.default')

@section('content')

	@if(Auth::check())
	

	<!-- if logged in user -->

		<h1>hi buddy</h1>
		<a href='logout'>Logout</a>


	<!--if no logged in user -->
	@else

		<h1>Welcome to HumbleTales!</h1>

		<a href='login'>Login</a>
		<a href='signup'>Sign Up</a>
		<a href='library'>Library</a>
	@endif



@endsection