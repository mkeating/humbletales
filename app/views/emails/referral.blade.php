<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		Hi, 

		<div>
			<?php echo $name2; ?>, at email <?php echo $ref_email; ?>, thought you would like HumbleTales! 
			Sign up here: {{ URL::to('/referral/'.$id) }} and then you'll be taken to <?php echo $name2; ?>'s story to continue it. 
		</div>
	</body>
</html>