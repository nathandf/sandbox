<div id="<?=$componentId?>">
	<?php
		if ( isset( $positionList ) === false || !is_array( $positionList ) ) {
			$this->renderErrorMessage( "Error loading PositionList" );
		} else {
			if ( empty( $positionList ) ) {
				echo( "<div>No Positions to show</div>" );
			}
			echo( "<div class=\"g gg20\">" );
			foreach ( $positionList as $position ) {
				$this->loadComponent(
					"Snippets/Position",
					[
						"position" => $position
					]
				);
			}
			echo( "</div>" );
		}
	?>
</div>
<!-- <script defer src="<?=HOME?>public/components/Position.js"></script> -->