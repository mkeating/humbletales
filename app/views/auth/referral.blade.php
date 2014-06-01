@extends('layouts.default')

@section('content')
	<h1>Sign Up</h1>

	@if ($errors->has())
		<ul>
			{{ $errors->first('email', '<li>:message</li>') }}
			{{ $errors->first('password', '<li>:message</li>') }}
		</ul>
	@endif

	<?php echo $id; ?>

	{{ Form::open(array('url' => 'referral')) }}

		<p>
			{{ Form::label('name', 'Name:') }}<br />
			{{ Form::text('name', Input::old('name')) }}
		</p>

		<p>
			{{ Form::label('email', 'Email:') }}<br />
			{{ Form::text('email', Input::old('email')) }}
		</p>

		<p>
			{{ Form::label('password', 'Password:') }}<br />
			{{ Form::password('password', Input::old('password')) }}
		</p>
			{{ Form::hidden('next_id',  $id) }}

		<p>
			{{ Form::submit('Sign Up') }}
		</p>

	{{ Form::close() }}
@endsection