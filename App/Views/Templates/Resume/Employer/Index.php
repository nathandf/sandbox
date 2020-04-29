{{parent Layouts/ResumeBuilder.php}}

{{block head}}
{{/block}}

{{block body}}
	<?php $this->loadComponent( "Navigation/RapidResume/MainMenu" ); ?>
    <hr>
	<h1 class="tc p20"><?=$employer->name?></h1>
	<div class="w-max-lrg center tp10">
		<button class="--toggle-id button bg-green mb20 c-white bsh-w-hov" data-target_id="new-position-modal"><span class="mr10">New</span>+</button>
		<?php
			$this->loadComponent(
				"Modals/NewPosition",
				[ "csrf-token" => $this->getData( "csrf-token" ) ]
			);
		?>
		<hr>
		<div class="mt20">
			<?php
				$this->loadComponent(
					"Snippets/PositionList",
					[ "positionList" => $this->getData( "positionList" ) ]
				);
			?>
		</div>
	</div>
{{/block}}