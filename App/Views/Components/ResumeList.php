<div>
	<?php
		if ( isset( $resumeList ) === false || !is_array( $resumeList ) ) {
			$this->renderErrorMessage( "Error loading ResumeList" );
		} else {
			if ( empty( $resumeList ) ) {
				echo( "<div>No Resumes to show</div>" );
			}
			echo( "<div class=\"g-std gg20\">" );
			foreach ( $resumeList as $resume ) {
				$this->loadComponent( "Resume", [ "resume" => $resume ] );
			}
			echo( "</div>" );
		}
	?>
</div>