@extends('layouts.default')

@section('head')

{{ HTML::style('/css/home-style.css') }}
{{ HTML::style('http://fonts.googleapis.com/css?family=Chango') }}
{{ HTML::style('http://fonts.googleapis.com/css?family=Raleway:200') }}

@endsection

@section('content')
	

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
		      <a class="navbar-brand" href="#">HumbleTales</a>
		    </div>

		    <!-- Collect the nav links, forms, and other content for toggling -->
		    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		      <ul class="nav navbar-nav navbar-right">
		        <li><a href="/">Home</a></li>
		        <li class="active"><a href="library">Library</a></li>
		        <li><a href="logout">Logout</a></li>
		      </ul>
		    </div><!-- /.navbar-collapse -->
		  </div><!-- /.container-fluid -->
		</nav>
		<div class="workspace">
			<h1>The Tales so far....</h1>
	
			@foreach($tales as $tale)
				<div class="taleThumb">
					<a href='library/<?php echo $tale->id; ?>' ><?php echo $tale->title; ?></a><br>
				</div>
	
			@endforeach
		</div>
	
@endsection