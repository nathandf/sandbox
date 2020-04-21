<div id="blank-modal" class="dn bg-black-60 pf ofy-a brtl w100 h100 p20 --toggle-self">
	<div class="pr --modal-content w-max-med center bg-white bsh br5 mb20">
		<div class=" fr fs22 fw6 c-dark-gray mr10 p10 cp --toggle-id" data-target_id="blank-modal">x</div>
		<div class="clear"></div>
		<h3 class="tc">Modal Title</h2>
			<form action="<?=HOME?>" method="post">
			<input type="hidden" name="csrf-token" value="{$csrf_token}">
			<div class="p20 pb40">

			</div>
			<hr>
			<div class="p20">
				<button type="submit" class="button bg-blue c-white bsh-w-hov fr">Done</button>
				<div class="clear"></div>
			</div>
		</form>
	</div>
</div>
