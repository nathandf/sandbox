<!DOCTYPE html>
<html>
	<head>
		<?php $this->loadComponent( "Heads/CoreHead" ); ?>
		{{parentblock head}}
	</head>
	<body>
		{{parentblock body}}
		<?php echo( $this->getComponentScriptTags() ); ?>
	</body>
</html>