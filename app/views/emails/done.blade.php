<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		

		<div>
			<?php echo $title; ?> is finished. You can view it at {{ URL::to('/library/'.$id) }} 
		</div>
	</body>
</html>