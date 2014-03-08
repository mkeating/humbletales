@extends('layouts.default')

@section('content')
	<h1>Start a new Tale</h1>

	@if ($errors->has())
		<ul>
			{{ $errors->first('email', '<li>:message</li>') }}
			{{ $errors->first('password', '<li>:message</li>') }}
		</ul>
	@endif

	{{ Form::open(array('url' => 'new')) }}

		<p>
			{{ Form::label('title', 'Title:') }}<br />
			{{ Form::text('title', Input::old('title')) }}
		</p>

		<p>
			{{ Form::label('content', 'Content:') }}<br />
			{{ Form::textarea('content', Input::old('content')) }}
		</p>

		<p>
			{{ Form::label('emailNext', 'Email for the next person:') }}<br />
			{{ Form::text('emailNext', Input::old('emailNext')) }}
		</p>

		<p>
			{{ Form::submit('Send it along') }}
		</p>

	{{ Form::close() }}
@endsection