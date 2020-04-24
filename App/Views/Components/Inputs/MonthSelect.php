<?php
	// F = Full textual representation
	// M = Short textual representation (3 letters)
	// m = Numeric representation with leading zeros
	// n = Numeric representation without leading zeros
	$month_formats = [ "F", "M", "m", "n" ];

	// Set the default 'display' month format to 'F' or full textual representation
	// if not specified.
	if ( isset( $display_format ) === false || !in_array( $display_format, $month_formats ) ) {
		$display_format = "F";
	}

	// Set the default 'value' month format to 'n' or numbers without leading zeros
	// if not specified.
	if ( isset( $value_format ) === false || !in_array( $value_format, $month_formats ) ) {
		$value_format = "n";
	}
?>
<div>
	<select name="<?php echo( ( isset( $name ) ? $name : "month" ) ); ?>" class="inp" id="<?php echo( ( isset( $id ) ? $id : "" ) ); ?>">
		<?php for( $month = 1; $month <= 12; $month++ ): ?>
		<option value="<?php echo( date( $value_format, mktime( 0, 0, 0, $month, 1, date( "Y" ) ) ) ); ?>"><?php echo( date( $display_format, mktime( 0, 0, 0, $month, 1, date( "Y" ) ) ) ); ?></option>
		<?php endfor; ?>
	</select>
</div>