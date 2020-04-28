<div id="new-certification-modal" class="dn bg-black-60 pf ofy-a brtl w100 h100 p20 --toggle-self">
	<div class="pr --modal-content w-max-med center bg-white bsh br5 mb20">
		<div class="fr fs22 fw6 c-dark-gray mr10 p10 cp --toggle-id" data-target_id="new-certification-modal">x</div>
		<div class="clear"></div>
		<h3 class="tc">Certification</h2>
			<form action="<?=HOME?>resume/certification/create" method="post">
			<input type="hidden" name="csrf-token" value="<?=$csrf_token?>">
			<div class="p20 pb40">
				<p class="label">Certification name</p>
				<input type="text" name="name" class="inp">
				<p class="label mt10">Description</p>
				<textarea name="description" class="textarea"></textarea>
				<p class="label mt10">Issued by</p>
				<input type="text" name="issued-by" class="inp">
				<p class="label mt10">Date awarded</p>
				<input type="text" name="date-awarded" class="inp">
			</div>
			<hr>
			<div class="p20">
				<button type="submit" class="button bg-blue c-white bsh-w-hov fr">Save</button>
				<div class="clear"></div>
			</div>
		</form>
	</div>
</div>
