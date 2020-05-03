<div>
	<?php
		if ( isset( $employerList ) === false || !is_array( $employerList ) ) {
			$this->renderErrorMessage( "Error loading EmployerList" );
		} else {
			if ( empty( $employerList ) ) {
				echo( "<div>No Employers to show</div>" );
			}
			echo( "<div class=\"g gg20\">" );
			foreach ( $employerList as $employer ) {
				$this->loadComponent( "Employer", [ "employer" => $employer ] );
			}
			echo( "</div>" );
		}
	?>
</div>