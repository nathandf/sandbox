<div class="bg-white br5 bsh">
	<div class="p20">
		<p class="fw6 c-dark-gray fs22"><?=$position->name?></p>
		<?php if( !$position->currently_employed ): ?>
			<?=$position->month_start?> <?=$position->year_start?> - <?=$position->month_end?> <?=$position->year_end?>
		<?php else: ?>
			<?=$position->month_start?> <?=$position->year_start?> - Present
		<?php endif; ?>
	</div>
	<?php if ( !empty( $position->dutyList ) ): ?>
		<hr>
		<div class="p20 g gtc-mca">
			<?php foreach( $position->dutyList as $duty ): ?>
				<p class="mr10 fw6 fs16">•</p>
				<p class="fs16 c-dark-gray fw6 mb10"><?=$duty->description?></p>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<hr>
	<div class="p10">
		<button class="c-dark-gray fr text-button"><i class="fas fa-ellipsis-h"></i></button>
		<div class="clear"></div>
	</div>
</div>