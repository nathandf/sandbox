<div>
	<select name="year" class="inp" id="">
		<?php
			$current_year = date( "Y" );
			$max_year = $current_year - 100;

			// Create options for the current year all the way back to 80 years.
			for ( $year = $current_year; $year >= $max_year; $year-- ) {
				echo( "<option value='{$year}'>{$year}</option>" );
			}
		?>
	</select>
</div>