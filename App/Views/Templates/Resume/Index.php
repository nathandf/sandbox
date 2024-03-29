{{parent Layouts/ResumeBuilder.php}}

{{block head}}
{{/block}}

{{block body}}
	<?php $this->loadComponent( "Navigation/RapidResume/MainMenu" ); ?>
    <hr>
	<h1 class="tc p20">Rapid Resumés</h1>
	<div class="w-max-lrg center tp10">
		<button class="button bg-green mb20 c-white bsh-w-hov"><span class="mr10">New</span>+</button>
		<hr>
		<div class="mt20">
			<?php
				$this->loadComponent(
					"Snippets/ResumeList",
					[ "resumeList" => $this->getData( "resumeList" ) ]
				);
			?>
		</div>
	</div>
{{/block}}