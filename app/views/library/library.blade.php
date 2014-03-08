@extends('layouts.default')

@section('content')
	<h1>All the Tales</h1>

	@foreach($tales as $tale)
		<a href='library/<?php echo $tale->id; ?>' ><?php echo $tale->title; ?></a><br>
	
	@endforeach

	
@endsection