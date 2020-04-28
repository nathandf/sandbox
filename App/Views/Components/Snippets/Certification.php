<div class="bg-white br5 bsh">
	<div class="p20">
		<p class="fw6 c-dark-gray fs20"><?=$certification->name?></p>
		<p class="c-muted"><?=$certification->date_awarded?></p>
		<?php if ( isset( $certification->issued_by ) ): ?>
			<p class="c-dark-gray">Issued by: <strong><?=$certification->issued_by?></strong></p>
		<?php endif; ?>
		<?php if ( isset( $certification->description ) ): ?>
			<p class="c-dark-gray mt20"><?=$certification->description?></p>
		<?php endif; ?>
	</div>
	<hr>
	<div class="p10">
		<form id="form-<?=$componentId?>"action="<?=HOME?>resume/certification/<?=$certification->id?>/delete" method="post">
			<input type="hidden" name="csrf-token" value="<?=$csrf_token?>">
			<button type="submit" class="--confirm c-dark-gray fr text-button" data-confirm_message="Delete this certification?"><i class="fas fa-trash"></i></button>
		</form>
		<div class="clear"></div>
	</div>
</div>