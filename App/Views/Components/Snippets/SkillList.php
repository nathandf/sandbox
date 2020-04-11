<div>
	<?php
		if ( isset( $skillList ) === false || !is_array( $skillList ) ) {
			$this->renderErrorMessage( "Error loading SkillList" );
		} else {
			if ( empty( $skillList ) ) {
				echo( "<div>No Skills to show</div>" );
			}
			echo( "<div class=\"g-std gg20\">" );
			foreach ( $skillList as $skill ) {
				$this->loadComponent( "Snippets/Skill", [ "skill" => $skill ] );
			}
			echo( "</div>" );
		}
	?>
</div>