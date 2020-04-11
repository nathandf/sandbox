<div class="bg-white br5 bsh">
	<div class="p20">
		<p class="fw6 fs18 tc tt-u"><?=$education->institution?></p>
		<?php if( !$education->currently_attending ): ?>
			<p class="fw6 fs16 tc mt20"><?=$education->award?></p>
			<p class="c-muted tc"><?=$education->month_graduated?> <?=$education->year_graduated?></p>
		<?php else: ?>
			<p class="c-muted tc"><i>Currently Attending</i></p>
			<p class="c-muted fw6 fs16 tc mt20"><?=$education->award?></p>
		<?php endif; ?>
	</div>
	<hr>
	<div class="p10">
		<button class="c-dark-grey fr hov-bsh button"><i class="fas fa-ellipsis-h"></i></button>
		<div class="clear"></div>
	</div>
</div>