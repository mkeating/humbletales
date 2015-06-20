@extends('layouts.default')

@section('head')

{{ HTML::style('/css/library-style.css') }}

@endsection


@section('content')

	


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
		        <li><a href="/">Home</a></li>
		        <li class="active"><a href="/library">Library</a></li>
		        <li><a href="/logout">Logout</a></li>
		      </ul>
		    </div><!-- /.navbar-collapse -->
		  </div><!-- /.container-fluid -->
		</nav>

		<div class="workspace">

			<h1><?php echo $title ?></h1>
			<div class="row">
				<div class="content col-lg-10 col-md-10 col-sm-12 col-xs-12">
					<?php echo $content ?>
				</div>
				<br>
				<br>
				<div class="authors col-lg-2 col-md-2 col-sm-12 col-xs-12">
					<?php echo $author ?>
				</div>
			</div>

		</div>
	
@endsection

@section('scripts')

{{ HTML::script('/js/tale.js') }}

@endsection