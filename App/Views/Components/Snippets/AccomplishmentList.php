<div>
	<?php
		if ( isset( $accomplishmentList ) === false || !is_array( $accomplishmentList ) ) {
			$this->renderErrorMessage( "Error loading AccomplishmentList" );
		} else {
			if ( empty( $accomplishmentList ) ) {
				echo( "<div>No Accomplishments to show</div>" );
			}
			echo( "<div class=\"g gg20\">" );
			foreach ( $accomplishmentList as $accomplishment ) {
				$this->loadComponent( "Snippets/Accomplishment", [ "accomplishment" => $accomplishment ] );
			}
			echo( "</div>" );
		}
	?>
</div>