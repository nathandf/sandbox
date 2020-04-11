<div>
	<?php
		if ( isset( $educationList ) === false || !is_array( $educationList ) ) {
			$this->renderErrorMessage( "Error loading EducationList" );
		} else {
			if ( empty( $educationList ) ) {
				echo( "<div>No Education to show</div>" );
			}
			echo( "<div class=\"g-std gg20\">" );
			foreach ( $educationList as $education ) {
				$this->loadComponent( "Snippets/Education", [ "education" => $education ] );
			}
			echo( "</div>" );
		}
	?>
</div>