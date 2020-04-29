<div>
	<?php if ( isset( $label ) ): ?>
	<p class="label"><?=$label?></p>
	<?php endif; ?>
	<select name="<?php echo( ( isset( $name ) ? $name : "year" ) ); ?>" class="inp" id="<?php echo( ( isset( $id ) ? $id : "" ) ); ?>">
		<?php
			$current_year = date( "Y" );
			$max_year = $current_year - ( ( isset( $range ) && is_int( $range ) && $range >= 1 ) ? $range : 100 );

			echo( "<option value='{$current_year}' selected=\"selected\">{$current_year}</option>" );

			$current_year--;

			// Create options for the current year all the way back to 80 years.
			for ( $year = $current_year; $year >= $max_year; $year-- ) {
				echo( "<option value='{$year}'>{$year}</option>" );
			}
		?>
	</select>
</div>