<div>
	<?php
		if ( isset( $certificationList ) === false || !is_array( $certificationList ) ) {
			$this->renderErrorMessage( "Error loading CertificationList" );
		} else {
			if ( empty( $certificationList ) ) {
				echo( "<div>No Certifications to show</div>" );
			}
			echo( "<div class=\"g gg20\">" );
			foreach ( $certificationList as $certification ) {
				$this->loadComponent( "Snippets/Certification", [ "certification" => $certification ] );
			}
			echo( "</div>" );
		}
	?>
</div>