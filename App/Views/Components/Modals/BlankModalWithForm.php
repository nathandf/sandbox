<div id="{modal_name}-modal" class="dn bg-black-60 pa br w100 h100 p20 --modal-overlay">
	<div class="pr --modal-content w-max-med center bg-white bsh br5 mb20">
		<div class="--modal-close fr fs22 fw6 c-dark-gray mr10 p10 cp" data-modal="{modal_name}">x</div>
		<div class="clear"></div>
		<h3 class="tc">Modal Title</h2>
			<form action="<?=HOME?>" method="post">
			<input type="hidden" name="csrf_token" value="{$csrf_token}">
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
