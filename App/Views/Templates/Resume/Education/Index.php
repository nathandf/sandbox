{{parent Layouts/ResumeBuilder.php}}

{{block head}}
{{/block}}

{{block body}}
	<?php $this->loadComponent( "Navigation/RapidResume/MainMenu" ); ?>
    <hr>
	<h1 class="tc p20">Education</h1>
	<div class="w-max-lrg center tp10">
		<button class="--modal-trigger button bg-green mb20 c-white bsh-w-hov" data-modal="new-education"><span class="mr10">New</span>+</button>
		<?php
			$this->loadComponent(
				"Modals/NewEducation",
				[ "csrf-token" => $this->getData( "csrf-token" ) ]
			);
		?>
		<hr>
		<div class="mt20">
			<?php
				$this->loadComponent(
					"Snippets/EducationList",
					[ "educationList" => $this->getData( "educationList" ) ]
				);
			?>
		</div>
	</div>
{{/block}}

