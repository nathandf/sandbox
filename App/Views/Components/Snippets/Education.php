<div class="bg-white br5 bsh">
	<div class="p20">
		<p class="fw6 fs18 tc c-dark-gray tt-u"><?=$education->institution?></p>
		<?php if( !$education->currently_attending ): ?>
			<p class="fs16 tc"><?=$education->award?></p>
			<p class="c-muted tc"><?=$education->month_graduated?> <?=$education->year_graduated?></p>
		<?php else: ?>
			<p class="fw6 fs16 tc">&nbsp;</p>
			<p class="c-muted tc"><i>Currently Attending</i></p>
		<?php endif; ?>
	</div>
	<hr>
	<div class="p10">
		<button class="c-dark-gray fr text-button"><i class="fas fa-ellipsis-h"></i></button>
		<div class="clear"></div>
	</div>
</div>