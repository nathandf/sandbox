{{parent Layouts/ResumeBuilder.php}}

{{block head}}
{{/block}}

{{block body}}
	<?php $this->loadComponent( "Navigation/RapidResume/MainMenu" ); ?>
    <hr>
	<h1 class="tc p20">Education</h1>
	<div class="w-max-lrg center tp10">
		<button class="button bg-green mb20 c-white bsh-w-hov"><span class="mr10">New</span>+</button>
		<div id="education-form" class="bg-white bsh br5 mb20">
			<form action="<?=HOME?>" method="post">
				<div class="p20">
					<input type="hidden" name="csrf_token" value="">
					<div>
						<p class="label">Institution</p>
						<input type="text" name="institution" class="inp" placeholder="University, High School, Program, etc.">
					</div>
					<div class="g g2 gg20 mt20">
						<div>
							<p class="label">City</p>
							<input type="text" name="city" class="inp">
						</div>
						<div>
							<p class="label">State</p>
							<input type="text" name="state" class="inp">
						</div>
					</div>
					<div class="mt20">
						<input id="currently_attending" type="checkbox" class="checkbox" name="currently_attending">
						<label class="label" for="currently_attending">Currently attending</label>
					</div>
					<div class="g g2 gg20 mt20">
						<div>
							<p class="label">Month graduated</p>
							<?php $this->loadComponent( "Inputs/MonthSelect" ); ?>
						</div>
						<div>
							<p class="label">Year graduated</p>
							<?php $this->loadComponent( "Inputs/YearSelect" ); ?>
						</div>
					</div>
					<div class="mt20">
						<p class="label">Degree awarded</p>
						<input type="text" name="award" class="inp" placeholder="B.S. (Bachelor of Science), High School Diploma">
					</div>
				</div>
				<hr>
				<div class="p20">
					<button class="button bg-blue c-white">Create</button>
				</div>
			</form>
		</div>
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

