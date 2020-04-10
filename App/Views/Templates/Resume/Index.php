{{parent Layouts/ResumeBuilder.php}}

{{block head}}
{{/block}}

{{block body}}
	<?php $this->loadComponent( "Navigation/RapidResume/MainMenu" ); ?>
    <hr>
	<h1 class="tc p20">Rapid Resumé</h1>
	<div class="w-max-xxlrg center p20">
		<?php
			$this->loadComponent( "Snippets/ResumeList", [ "resumeList" => $this->getData( "resumeList" ) ] );
		?>
	</div>
{{/block}}