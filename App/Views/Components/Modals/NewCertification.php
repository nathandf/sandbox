<div id="new-certification-modal" class="dn bg-black-60 pa br w100 h100 p20 --modal-overlay">
	<div class="pr --modal-content w-max-med center bg-white bsh br5 mb20">
		<div class="--modal-close fr fs22 fw6 c-dark-gray mr10 p10 cp" data-modal="new-certification">x</div>
		<div class="clear"></div>
		<h3 class="tc">Certification</h2>
			<form action="<?=HOME?>certification/create" method="post">
			<input type="hidden" name="csrf_token" value="{$csrf_token}">
			<div class="p20 pb40">
				<p class="label">Certification name</p>
				<input type="text" name="name" class="inp">
				<p class="label mt10">Description</p>
				<textarea name="description" class="textarea"></textarea>
				<p class="label mt10">Issued by</p>
				<input type="text" name="issued_by" class="inp">
				<p class="label mt10">Date awarded</p>
				<input type="text" name="date_awarded" class="inp">
			</div>
			<hr>
			<div class="p20">
				<button type="submit" class="button bg-blue c-white bsh-w-hov fr">Save</button>
				<div class="clear"></div>
			</div>
		</form>
	</div>
</div>
