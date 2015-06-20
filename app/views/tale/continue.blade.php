@extends('layouts.default')

@section('content')
	<h1>Continue the Tale</h1>

	@if ($errors->has())
		<ul>
			{{ $errors->first('email', '<li>:message</li>') }}
			{{ $errors->first('password', '<li>:message</li>') }}
		</ul>
	@endif

	<strong><?php echo $title; ?></strong>
	<strong><?php echo $content; ?></strong>


	@if ($section == 3)

		{{ Form::open(array('url' => 'continue')) }}


			<p>
				{{ Form::label('content', 'Content:') }}<br />
				{{ Form::textarea('content', Input::old('content')) }}
			</p>

			<p>
				{{ Form::submit('Finish the Tale') }}
			</p>

		{{ Form::close() }}

	
	@else
	

		{{ Form::open(array('url' => 'continue')) }}


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
		
	@endif

	
@endsection