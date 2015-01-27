@extends('layouts.default')

@section('content')

	

	@if(Auth::check())
	<!-- if logged in user -->

		

		<nav class="navbar navbar-default" role="navigation">
		  <div class="container-fluid">
		    <!-- Brand and toggle get grouped for better mobile display -->
		    <div class="navbar-header">
		      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
		        <span class="sr-only">Toggle navigation</span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		      </button>
		      <a class="navbar-brand" href="#">HumbleTales</a>
		    </div>

		    <!-- Collect the nav links, forms, and other content for toggling -->
		    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		      <ul class="nav navbar-nav navbar-right">
		        <li class="active"><a href="/">Home</a></li>
		        <li><a href="library">Library</a></li>
		        <li><a href="logout">Logout</a></li>
		      </ul>
		    </div><!-- /.navbar-collapse -->
		  </div><!-- /.container-fluid -->
		</nav>

		
		<div class="greeting"><?php echo $greeting; ?></div>
		<div class="workspace">

				<!-- if logged in user has a current tale -->
				@if(Auth::user()->current_tale != NULL)

					<h1>Continue the Tale</h1>

					@if ($errors->has())
						<ul>
							{{ $errors->first('email', '<li>:message</li>') }}
							{{ $errors->first('emailNext', '<li>:message</li>') }}
						</ul>
					@endif

					<div class="title"><?php echo $title; ?></div>
					<br/>
					<br/>
					<div class="content"><?php echo $content; ?></div>


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


							@if (Auth::user()->been_refused != 1)
							<p>
								{{ Form::label('content', 'Content:') }}<br />
								{{ Form::textarea('content', Input::old('content')) }}
							</p>
							@endif

							<p>
								{{ Form::label('emailNext', 'Email for the next person:') }}<br />
								{{ Form::text('emailNext', Input::old('emailNext')) }}
							</p>

							<p>
								{{ Form::submit('Send it along') }}
							</p>

						{{ Form::close() }}
						
					@endif

				<!-- if logged in user has no current tale, display Start a new tale -->
				@else

				<h1>Start a new Tale</h1>

					@if ($errors->has())
						<ul>
							{{ $errors->first('title', '<li>:message</li>') }}
							{{ $errors->first('emailNext', '<li>:message</li>') }}
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


				@endif
		</div>
	
	<!--if no logged in user -->
	@else

		<h1>Welcome to HumbleTales!</h1>
		<h2>Asychronous, sequential, collaborative short fiction. It's like the Telephone game.</h2>
		<h3><a href="library">Read some stories</a></h3>
		<h3><a href="login">Login</a></h3>

		<h1>Sign Up</h1>

			@if ($errors->has())
				<ul>
					{{ $errors->first('name', '<li>:message</li>') }}
					{{ $errors->first('email', '<li>:message</li>') }}
					{{ $errors->first('password', '<li>:message</li>') }}
				</ul>
			@endif

			{{ Form::open(array('url' => 'signup')) }}

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

				<p>
					{{ Form::submit('Sign Up') }}
				</p>

			{{ Form::close() }}
		
	@endif
	


@endsection