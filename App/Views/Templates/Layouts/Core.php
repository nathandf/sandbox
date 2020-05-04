<!DOCTYPE html>
<html>
	<head>
		<title><?php echo( $this->title ?? "Quickboy App" ); ?></title>
		<meta name="description" content="<?php echo( $this->description ?? "This app was made with Quickboy. Wow so fast." ); ?>">
		<?php $this->loadComponent( "Heads/CoreHead" ); ?>
		{{parentblock head}}
	</head>
	<body>
		{{parentblock body}}
		<?php $this->renderScriptTags(); ?>
	</body>
</html>