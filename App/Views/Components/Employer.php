<div class="bg-white br5 bsh">
	<div class="p20">
		<a class="link-wrap" href="<?=HOME?>resume/employer/<?=$employer->id?>/"><p class="fw6 c-dark-gray fs22"><?=$employer->name?></p></a>
	</div>
	<hr>
	<div class="p10">
		<a href="<?=HOME?>resume/employer/<?=$employer->id?>/" class="c-dark-gray fr text-button"><i class="fas fa-pencil-alt"></i></a>
		<form id="form-<?=$componentId?>"action="<?=HOME?>resume/employer/<?=$employer->id?>/delete" method="post">
			<input type="hidden" name="csrf-token" value="<?=$csrf_token?>">
			<button type="submit" class="--confirm c-dark-gray fr text-button mr10" data-confirm_message="Delete this employer?"><i class="fas fa-trash"></i></button>
		</form>
		<div class="clear"></div>
	</div>
</div>