<div id="new-education-modal" class="dn bg-black-60 pa br w100 h100 p20 --modal-overlay">
	<div class="pr --modal-content w-max-med center bg-white bsh br5 mb20">
		<div class="--modal-close fr fs22 fw6 c-dark-gray mr10 p10 cp" data-modal="new-education">x</div>
		<div class="clear"></div>
		<h3 class="tc">Education</h2>
		<form action="<?=HOME?>education/create" method="post">
			<input type="hidden" name="csrf_token" value="{$csrf_token}">
			<div class="p20 pb40">
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
						<p class="label">Month</p>
						<?php $this->loadComponent( "Inputs/MonthSelect" ); ?>
					</div>
					<div>
						<p class="label">Year</p>
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
				<button type="submit" class="button bg-blue c-white bsh-w-hov fr">Save</button>
				<div class="clear"></div>
			</div>
		</form>
	</div>
</div>
