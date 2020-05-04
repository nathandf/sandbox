{{parent Layouts/ResumeBuilder.php}}

{{block head}}

{{/block}}

{{block body}}
	<?php $this->loadComponent( "Navigation/RapidResume/MainMenu" ); ?>
    <hr>
	<div class="w-max-lrg center tp10">
		<div contenteditable class="tc p20 fs26 fw6 mt20 mb20"><?=$employer->name?></div>
		<button class="--modal-trigger button bg-green mb20 c-white bsh-w-hov" data-modal="new-position"><span class="mr10">New</span>+</button>
		<?php
			$this->loadComponent(
				"Modals/NewPosition",
				[
					"csrf-token" => $this->getData( "csrf-token" ),
					"employer" => $employer
				]
			);
		?>
		<hr>
		<div class="mt20">
			<?php
				$this->loadComponent(
					"PositionList",
					[ "positionList" => $this->getData( "positionList" ) ]
				);
			?>
		</div>
	</div>
{{/block}}