<div class="bg-white br5 bsh">
	<div class="p20">
		<p class="c-dark-gray fs16 fw6"><?=$accomplishment->description?></p>
	</div>
	<hr>
	<div class="p10">
		<form id="form-<?=$componentId?>"action="<?=HOME?>resume/accomplishment/<?=$accomplishment->id?>/delete" method="post">
			<input type="hidden" name="csrf-token" value="<?=$csrf_token?>">
			<button type="submit" class="--confirm c-dark-gray fr text-button" data-confirm_message="Delete this accomplishment?"><i class="fas fa-trash"></i></button>
		</form>
		<div class="clear"></div>
	</div>
</div>