<div class="bg-white br5 bsh">
	<div class="p20">
		<p class="fw6 c-dark-gray fs22"><?=$employment->position?></p>
		<p><?=$employment->employer->name?></p>
		<?php if( !$employment->currently_employed ): ?>
			<p class="c-muted"><?php echo( $this->numToMonth( $employment->month_start ) ); ?> <?=$employment->year_start?> - <?php echo( $this->numToMonth( $employment->month_end ) ); ?> <?=$employment->year_end?></p>
		<?php else: ?>
			<p class="c-muted"><?php echo( $this->numToMonth( $employment->month_start ) ); ?> <?=$employment->year_start?> - <i>Present</i></p>
		<?php endif; ?>
	</div>
	<?php if ( !empty( $employment->dutyList ) ): ?>
		<hr>
		<div class="p20 g gtc-mca">
			<?php foreach( $employment->dutyList as $duty ): ?>
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