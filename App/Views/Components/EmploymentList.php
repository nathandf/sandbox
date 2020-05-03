<div>
	<?php
		if ( isset( $employmentList ) === false || !is_array( $employmentList ) ) {
			$this->renderErrorMessage( "Error loading EmploymentList" );
		} else {
			if ( empty( $employmentList ) ) {
				echo( "<div>No Employments to show</div>" );
			}
			echo( "<div class=\"g gg20\">" );
			foreach ( $employmentList as $employment ) {
				$this->loadComponent( "Employment", [ "employment" => $employment ] );
			}
			echo( "</div>" );
		}
	?>
</div>