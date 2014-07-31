<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		Hi <?php echo $name1; ?>, 

		<div>
			<?php echo $name2; ?> would like you to continue their story. Log in at {{ URL::to('/') }} to start writing!

			Don't feel like it? Click here to refuse: {{ URL::to('/refusal/'.$id.'/'.$secret)}}

		
		</div>
	</body>
</html>