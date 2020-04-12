<div>
	<?php
		if ( isset( $experienceList ) === false || !is_array( $experienceList ) ) {
			$this->renderErrorMessage( "Error loading ExperienceList" );
		} else {
			if ( empty( $experienceList ) ) {
				echo( "<div>No Experiences to show</div>" );
			}
			echo( "<div class=\"g gg20\">" );
			foreach ( $experienceList as $experience ) {
				$this->loadComponent( "Snippets/Experience", [ "experience" => $experience ] );
			}
			echo( "</div>" );
		}
	?>
</div>