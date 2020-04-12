<div class="bg-white br5 bsh">
	<div class="p20">
		<p class="fw6 fs22"><?=$experience->position?></p>
		<p><?=$experience->employer->name?></p>
		<?php if( !$experience->present ): ?>
			<p class="c-muted"><?php echo( $this->numToMonth( $experience->month_start ) ); ?> <?=$experience->year_start?> - <?php echo( $this->numToMonth( $experience->month_end ) ); ?> <?=$experience->year_end?></p>
		<?php else: ?>
			<p class="c-muted"><?php echo( $this->numToMonth( $experience->month_start ) ); ?> <?=$experience->year_start?> - <i>Present</i></p>
		<?php endif; ?>
	</div>
	<?php if ( !empty( $experience->dutyList ) ): ?>
		<hr>
		<div class="p20">
			<?php foreach( $experience->dutyList as $duty ): ?>
				<p class="c-dark-gray fs16 fw6 mb10"><span class="mr10 fw6 fs16">•</span><?=$duty->description?></p>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<hr>
	<div class="p10">
		<button class="c-dark-gray fr text-button"><i class="fas fa-ellipsis-h"></i></button>
		<div class="clear"></div>
	</div>
</div>