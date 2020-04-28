<div id="new-education-modal" class="dn bg-black-60 pf ofy-a brtl w100 h100 p20 --toggle-self">
	<div class="pr --modal-content w-max-med center bg-white bsh br5 mb20">
		<div class="fr fs22 fw6 c-dark-gray mr10 p10 cp --toggle-id" data-target_id="new-education-modal">x</div>
		<div class="clear"></div>
		<h3 class="tc">Education</h2>
		<form action="<?=HOME?>resume/education/create" method="post">
			<input type="hidden" name="csrf-token" value="<?=$csrf_token?>">
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
					<input id="currently-attending" type="checkbox" class="checkbox --checked-hide-id" name="currently-attending" data-target_id="graduated" value="1">
					<label class="label" for="currently-attending">Currently attending</label>
				</div>
				<div id="graduated" class="mt20">
					<h3>Graduation</h3>
					<div class="g g2 gg20 mt10">
						<div>
							<p class="label">Month</p>
							<?php
								$this->loadComponent(
									"Inputs/MonthSelect",
									[
										"name" => "month-graduated",
										"display-format" => "F",
										"value-format" => "n"
									]
								);
							?>
						</div>
						<div>
							<p class="label">Year</p>
							<?php
								$this->loadComponent(
									"Inputs/YearSelect",
									[
										"name" => "year-graduated",
										"range" => 80
									]
								);
							?>
						</div>
					</div>
					<div class="mt20">
						<p class="label">Degree awarded</p>
						<input type="text" name="award" class="inp" placeholder="B.S. (Bachelor of Science), High School Diploma">
					</div>
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
