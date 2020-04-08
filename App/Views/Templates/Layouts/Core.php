<!DOCTYPE html>
<html>
	<head>
		<?php include( "App/Views/Components/Heads/CoreHead.php" ); ?>
		{{parentblock head}}
	</head>
	<body>
		{{parentblock body}}
		<script defer src="<?=HOME?>public/jqueryui/js/jquery.js"></script>
		<script defer src="<?=HOME?>public/jqueryui/js/jquery-ui.js"></script>
		<script defer src="<?=HOME?>public/jqueryui/js/jquery.ui.touch-punch.min.js"></script>
		<script defer src="<?=HOME?>public/js/main.js"></script>
	</body>
</html>