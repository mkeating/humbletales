<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		Hi <?php echo $name1; ?>, 

		<div>
			<?php echo $name2; ?> would like you to continue their story. Log in at {{ URL::to('/') }} to start writing!

			Rather not? Click {{ URL::to('/refusal/'.$id)}}
		</div>
	</body>
</html>