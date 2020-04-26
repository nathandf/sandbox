<div id="<?=$componentId?>" class="bg-white br5 bsh">
	<div class="p20">
		<p class="fw6 fs18 tc c-dark-gray tt-u"><?=$education->institution?></p>
		<?php if( !$education->currently_attending ): ?>
			<p class="fs16 tc"><?=$education->award?></p>
			<p class="c-muted tc"><?php echo( date( "M", mktime( 0, 0, 0, (int) $education->month_graduated, 1, date( "Y" ) ) ) ); ?> <?=$education->year_graduated?></p>
		<?php else: ?>
			<p class="fw6 fs16 tc">&nbsp;</p>
			<p class="c-muted tc"><i>Currently Attending</i></p>
		<?php endif; ?>
	</div>
	<hr>
	<div class="p10">
		<form id="form-<?=$componentId?>"action="<?=HOME?>resume/education/<?=$education->id?>/delete" method="post">
			<input type="hidden" name="csrf-token" value="{$csrf_token}">
			<button type="submit" class="--confirm --remove-id c-dark-gray fr text-button" data-remove_id="<?=$componentId?>" data-confirm_message="Delete education history for '<?=$education->institution?>'?"><i class="fas fa-trash"></i></button>
		</form>
		<div class="clear"></div>
	</div>
</div>