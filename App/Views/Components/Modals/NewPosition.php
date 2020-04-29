<?php if ( isset( $employer ) === false ): ?>
<?php $this->renderErrorMessage( "Cannot load new position modal without employer data" ); ?>
<?php else: ?>
<div id="new-position-modal" class="dn bg-black-60 pf ofy-a brtl w100 h100 p20 --toggle-self">
	<div class="pr --modal-content w-max-xxmed center bg-white bsh br5 mb20">
		<div class="--toggle-id fr fs22 fw6 c-dark-gray mr10 p10 cp" data-target_id="new-position-modal">x</div>
		<div class="clear"></div>
		<h3 class="tc">Add a new position</h2>
		<form action="<?=HOME?>resume/position/create" method="post">
			<input type="hidden" name="csrf-token" value="<?=$csrf_token?>">
			<input type="hidden" name="employer-id" value="<?=$employer->id?>">
			<div class="p20">
				<div>
					<p class="label">Name</p>
					<input type="text" name="name" class="inp" placeholder="Marketing Director, Salesperson, etc">
				</div>
			</div>
			<hr>
			<div class="p20">
				<h3>Start</h3>
				<div class="g g2 gg20 mt10">
					<div>
						<?php
							$this->loadComponent(
								"Inputs/MonthSelect",
								[
									"name" => "start-month",
									"display-format" => "F",
									"value-format" => "n"
								]
							);
						?>
					</div>
					<div>
						<?php
							$this->loadComponent(
								"Inputs/YearSelect",
								[
									"name" => "start-year",
									"range" => 80
								]
							);
						?>
					</div>
				</div>
			</div>
			<hr>
			<div class="p20">
				<h3>End</h3>
				<div class="mt10">
					<input id="currently-employed" type="checkbox" class="checkbox --checked-hide-id" name="currently-employed" value="1" data-target_id="end-date-selects">
					<label class="label" for="currently-employed">I'm currently employed in this position</label>
				</div>
				<div id="end-date-selects" class="g g2 gg20 mt10 mb20">
					<div>
						<?php
							$this->loadComponent(
								"Inputs/MonthSelect",
								[
									"name" => "end-month",
									"display-format" => "F",
									"value-format" => "n"
								]
							);
						?>
					</div>
					<div>
						<?php
							$this->loadComponent(
								"Inputs/YearSelect",
								[
									"name" => "end-year",
									"range" => 80
								]
							);
						?>
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
<?php endif; ?>