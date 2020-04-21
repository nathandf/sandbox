<div id="new-employer-modal" class="dn bg-black-60 pf ofy-a brtl w100 h100 p20 --toggle-self">
	<div class="pr --modal-content w-max-xxmed center bg-white bsh br5 mb20">
		<div class="--toggle-id fr fs22 fw6 c-dark-gray mr10 p10 cp" data-target_id="new-employer-modal">x</div>
		<div class="clear"></div>
		<h3 class="tc">Add a new employer</h2>
		<form action="<?=HOME?>resume/employer/create" method="post">
			<input type="hidden" name="csrf-token" value="{$csrf_token}">
			<div class="p20">
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
			</div>
			<hr>
			<div class="p20">
				<button type="submit" class="button bg-blue c-white bsh-w-hov fr">Save</button>
				<div class="clear"></div>
			</div>
		</form>
	</div>
</div>
