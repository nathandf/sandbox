{{parent Layouts/ResumeBuilder.php}}

{{block head}}
{{/block}}

{{block body}}
	<?php $this->loadComponent( "Navigation/RapidResume/MainMenu" ); ?>
    <hr>
	<h1 class="tc p20">Accomplishments</h1>
	<div class="w-max-lrg center tp10">
		<button class="button bg-green mb20 c-white bsh-w-hov"><span class="mr10">New</span>+</button>
		<div>
			<form action="<?=HOME?>resume/skills/new">
				<input type="hidden" name="csrf_token" value="<?php $this->echoData( "csrf_token" ); ?>">
				<p class="label">Accomplishment description</p>
				<textarea name="description" class="textarea mb20"></textarea>
			</form>
		</div>
		<hr>
		<div class="mt20">
			<?php
				$this->loadComponent(
					"Snippets/AccomplishmentList",
					[ "accomplishmentList" => $this->getData( "accomplishmentList" ) ]
				);
			?>
		</div>
	</div>
{{/block}}