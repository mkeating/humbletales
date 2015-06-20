@extends('layouts.default')

@section('content')
	<div class="col-lg-3 col-md-3"></div>
	
	<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
	<h1>Login</h1>

	@if ($errors->has())
		<ul>
			{{ $errors->first('email', '<li>:message</li>') }}
			{{ $errors->first('password', '<li>:message</li>') }}
		</ul>
		
	@endif

	

	{{ Form::open(array('url' => 'login')) }}

		<p>
			{{ Form::label('email', 'Email:') }}<br />
			{{ Form::text('email', Input::old('email')) }}
		</p>

		<p>
			{{ Form::label('password', 'Password:') }}<br />
			{{ Form::password('password', Input::old('password')) }}
		</p>

		<p>
			{{ Form::submit('Login') }}
		</p>

	{{ Form::close() }}
	</div>
	<div class="col-lg-3 col-md-3"></div>

@endsection