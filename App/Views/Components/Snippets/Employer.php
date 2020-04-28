<div class="bg-white br5 bsh">
	<div class="p20">
		<p class="fw6 c-dark-gray fs22"><?=$employer->name?></p>
	</div>
	<hr>
	<div class="p10">
		<form id="form-<?=$componentId?>"action="<?=HOME?>resume/employer/<?=$employer->id?>/delete" method="post">
			<input type="hidden" name="csrf-token" value="<?=$csrf_token?>">
			<button type="submit" class="--confirm c-dark-gray fr text-button" data-confirm_message="Delete this employer?"><i class="fas fa-trash"></i></button>
		</form>
		<div class="clear"></div>
	</div>
</div>