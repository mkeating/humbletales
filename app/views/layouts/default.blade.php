<!DOCTYPE html>
<html lang="en">
<head>
	<title>HumbleTales</title>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap 101 Template</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
 <style type="text/css">
 html, body, .wrap {
 	height:100%;
 }

 .wrap{
 	border:1px solid black;
 }
 	</style>
</head>
<body>
	@if(Session::has('message'))
		<p style="color:green;">{{ Session::get('message') }}</p>
	@endif

	<div class="container-fluid">
		<div class="row">
			<div class= "col-lg-1 col-md-1"></div>
			<div class = "col-lg-10 col-md-10 col-sm-12 col-xs-12 wrap">
				@yield('content')
			</div>
			<div class= "col-lg-1 col-md-1"></div>
			
		</div>
</div>

	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>