@extends('layouts.default')

@section('content')
	<h1><?php echo $title ?></h1>

	<div class='content'>
		<?php echo $content ?>
	</div>
	<br>
	<br>
	<div class='authors'>
		<?php echo $author ?>
	</div>
	
@endsection