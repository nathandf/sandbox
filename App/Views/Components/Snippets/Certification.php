<div class="bg-white br5 bsh">
	<div class="p20">
		<p class="fw6 fs22"><?=$certification->name?></p>
		<p class="c-muted"><?=$certification->date_awarded?></p>
		<?php if ( isset( $certification->issued_by ) ): ?>
			<p class="c-dark-gray">Issued by: <i><?=$certification->issued_by?></i></p>
		<?php endif; ?>
		<?php if ( isset( $certification->description ) ): ?>
			<p class="c-dark-gray mt20"><?=$certification->description?></p>
		<?php endif; ?>
	</div>
	<hr>
	<div class="p10">
		<button class="c-dark-gray fr text-button"><i class="fas fa-ellipsis-h"></i></button>
		<div class="clear"></div>
	</div>
</div>