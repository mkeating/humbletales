@extends('layouts.default')

@section('head')

{{ HTML::style('/css/home-style.css') }}
{{ HTML::style('http://fonts.googleapis.com/css?family=Chango') }}
{{ HTML::style('http://fonts.googleapis.com/css?family=Raleway:200') }}

@endsection

@section('content')

	

	@if(Auth::check())
	<!-- if logged in user -->

		

		<nav class="navbar navbar-HT navbar-default" role="navigation">
		  <div class="container-fluid">
		    <!-- Brand and toggle get grouped for better mobile display -->
		    <div class="navbar-header">
		      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
		        <span class="sr-only">Toggle navigation</span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		      </button>
		      <a class="navbar-brand" href="/">HumbleTales</a>
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

		
		
		<div class="workspace">

				<div class="greeting"><?php echo $greeting; ?></div>

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

						{{ Form::open(array('url' => 'continue', 'class' => 'storyForm')) }}


							<p>
								
								{{ Form::textarea('content', Input::old('content'), array('placeholder'=> 'Write your story here!')) }}
							</p>

							<p>
								{{ Form::submit('Finish the Tale') }}
							</p>

						{{ Form::close() }}

					
					@else
					


						{{ Form::open(array('url' => 'continue', 'class' => 'storyForm')) }}


							@if (Auth::user()->been_refused != 1)
							<p>
								
								{{ Form::textarea('content', Input::old('content'), array('placeholder'=> 'Write your story here!')) }}
							</p>
							@endif

							<p>
								
								{{ Form::text('emailNext', Input::old('emailNext'), array('placeholder'=> 'Email of the next person')) }}
							</p>

							<p>
								{{ Form::submit('Send it along') }}
							</p>

						{{ Form::close() }}
						
					@endif

				<!-- if logged in user has no current tale, display Start a new tale -->
				@else

				<h1>Start a new Tale!</h1>

					@if ($errors->has())
						<ul>
							{{ $errors->first('title', '<li>:message</li>') }}
							{{ $errors->first('emailNext', '<li>:message</li>') }}
						</ul>
					@endif

					{{ Form::open(array('url' => 'new', 'class' => 'storyForm')) }}

						<p>
							
							{{ Form::text('title', Input::old('title'), array('placeholder'=> 'Title', 'class'=>'storyTitle')) }}
						</p>

						<p>
							
							{{ Form::textarea('content', Input::old('content'), array('placeholder'=> 'Write your story here!', 'class'=>'storyContent')) }}
						</p>

						<p>
							
							{{ Form::text('emailNext', Input::old('emailNext'), array('placeholder'=> 'Email of the next person', 'class'=>'storyEmail')) }}
						</p>

						<p>
							{{ Form::submit('Send it along!', array('class'=> 'storySubmit')) }}
						</p>

					{{ Form::close() }}


				@endif
		
		</div>
	
	<!--if no logged in user -->
	@else

		<h1>HumbleTales</h1>
		<h2>Collaborative short fiction by email. It's like the Telephone game.</h2>

		<div class="leftHome col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<h2>Sign Up</h2>

			@if ($errors->has())
				<ul>
					{{ $errors->first('name', '<li>:message</li>') }}
					{{ $errors->first('email', '<li>:message</li>') }}
					{{ $errors->first('password', '<li>:message</li>') }}
				</ul>
			@endif

			{{ Form::open(array('url' => 'signup', 'id' => 'signupForm')) }}

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
		</div>
		<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
			<h1> OR </h1>
		</div>
		<div class="rightHome col-lg-4 col-md-4 col-sm-12 col-xs-12">
		
		<h3><a href="login">Login</a></h3>
		<h3><a href="library">Read some stories</a></h3>
		
		</div>
	@endif
	


@endsection