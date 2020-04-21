<div id="new-employment-modal" class="dn bg-black-60 pf ofy-a brtl w100 h100 p20 --toggle-self">
	<div class="pr --modal-content w-max-xxmed center bg-white bsh br5 mb20">
		<div class="--toggle-id fr fs22 fw6 c-dark-gray mr10 p10 cp" data-target_id="new-employment-modal">x</div>
		<div class="clear"></div>
		<h3 class="tc">Build your employment history</h2>
		<form action="<?=HOME?>resume/employment/create" method="post">
			<input type="hidden" name="csrf-token" value="{$csrf_token}">
			<div class="p20">
				<?php
					$display_class = null;
					if ( !empty( $this->getData( "employerList" ) ) ):
						$display_class = "dn";
				?>
					<div class="--new-employer-target">
						<p class="label">Your employers</p>
						<div>
							<?php
								$this->loadComponent(
									"Inputs/EmployerSelect",
									[ "employerList" => $employerList ]
								);
							?>
						</div>
						<button type="button" class="text-button mt20 --toggle-class --toggle-id" data-target_class="--new-employer-target" data-target_id="new-employer-modal"><span class="fw6 c-blue"><span class="mr10">New Employer</span>+</span></button>
					</div>
				<?php endif; ?>
				<div class="--new-employer-target <?=$display_class?>" id="new-employer-fields">
					<div>
						<p class="label">Company Name</p>
						<input type="text" name="name" class="inp" placeholder="Acme Inc.">
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
					<?php if ( !empty( $this->getData( "employerList" ) ) ): ?>
						<button type="button" class="text-button mt20 --toggle-class" data-target_class="--new-employer-target"><span class="fw6 c-blue"><span class="mr10">&lt;</span> My employers</span></button>
					<?php endif; ?>
				</div>
			</div>
			<hr>
			<div class="p20">
				<p class="label">Position</p>
				<input type="text" name="position" class="inp" placeholder="Sales Director">
			</div>
			<hr>
			<div class="p20">
				<h3>Start Date:</h3>
				<div class="g g2 gg20 mt10">
					<div>
						<p class="label">Month</p>
						<?php $this->loadComponent( "Inputs/MonthSelect" ); ?>
					</div>
					<div>
						<p class="label">Year</p>
						<?php $this->loadComponent( "Inputs/YearSelect" ); ?>
					</div>
				</div>
			</div>
			<hr>
			<div class="p20">
				<h3>End Date:</h3>
				<div class="mt10">
					<input id="currently-employed" type="checkbox" class="checkbox --checked-hide-id" name="currently-employed" value="1" data-target_id="end-date-selects">
					<label class="label" for="currently-employed">I'm currently employed in this position</label>
				</div>
				<div id="end-date-selects" class="g g2 gg20 mt10 mb20">
					<div>
						<p class="label">Month</p>
						<?php $this->loadComponent( "Inputs/MonthSelect" ); ?>
					</div>
					<div>
						<p class="label">Year</p>
						<?php $this->loadComponent( "Inputs/YearSelect" ); ?>
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
