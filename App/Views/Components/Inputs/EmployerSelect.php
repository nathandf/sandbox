<div>
	<?php if( isset( $employerList ) && is_array( $employerList ) ): ?>
	<select name="employer-id" class="inp" id="employer-select">
		<option hidden="hidden" selected="selected">Choose an employer</option>
		<?php foreach( $employerList as $employer ): ?>
			<option value="<?=$employer->id?>"><?=$employer->name?></option>
		<?php endforeach; ?>
	</select>
	<?php else: ?>
	<?php $this->renderErrorMessage( "Required data is missing to show employer list." ); ?>	
	<?php endif; ?>
</div>