<div class="bg-white br5 bsh<?php if ( !empty( $classes ) ) { echo( " " . implode( " ", $classes ) ); } ?>" id="<?php echo( $componentId ?? "" ); ?>">
	<div class="p20">
		<p contenteditable class="fw6 c-dark-gray fs22"><?=$position->name?></p>
		<?php if( !$position->currently_employed ): ?>
			<?=$position->start_month?> <?=$position->start_year?> - <?=$position->end_month?> <?=$position->end_year?>
		<?php else: ?>
			<?=$position->start_month?> <?=$position->start_year?> - Present
		<?php endif; ?>
	</div>
	<?php if ( !empty( $position->dutyList ) ): ?>
		<hr>
		<div id="<?=$componentId?>-duty-list" class="p20">
			<?php foreach( $position->dutyList as $duty ): ?>
				<div class="g gtc-mca">
					<p class="mr10 fw6 fs16">•</p>
					<p contenteditable class="fs16 c-dark-gray fw6 mb10"><?=$duty->description?></p>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<hr>
	<div class="p10">
		<button class="c-dark-gray fr text-button --add-duty" data-duty_list_id="<?=$componentId?>-duty-list"><i class="fas fa-plus"></i></button>
		<div class="clear"></div>
	</div>
</div>