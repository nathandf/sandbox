{{parent Layouts/ResumeBuilder.php}}

{{block head}}
{{/block}}

{{block body}}
	<?php $this->loadComponent( "Navigation/RapidResume/MainMenu" ); ?>
    <hr>
	<h1 class="tc p20">Experience</h1>
	<div class="w-max-lrg center tp10">
		<button class="--modal-trigger button bg-green mb20 c-white bsh-w-hov" data-modal="new-experience"><span class="mr10">New</span>+</button>
		<?php
			$this->loadComponent(
				"Modals/NewExperience",
				[ "csrf-token" => $this->getData( "csrf-token" ) ]
			);
		?>
		<hr>
		<div class="mt20">
			<?php
				$this->loadComponent(
					"Snippets/ExperienceList",
					[ "experienceList" => $this->getData( "experienceList" ) ]
				);
			?>
		</div>
	</div>
{{/block}}