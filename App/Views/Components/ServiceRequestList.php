<div id="service-requests">
<?php
	$serviceRequests = $this->getData( "serviceRequests" );
	if ( !is_null( $serviceRequests) ) {
		if ( count( $serviceRequests ) > 0 ) {
			foreach ( $serviceRequests as $serviceRequest ) {
				include( "App/Views/Components/ServiceRequest.php" );
			}
		} else {
			echo(
				"<p>No recent Service Requests</p>"
			);
		}
	} else {
		$this->renderErrorMessage( "Required data not set: 'serviceRequests'" );
	}
?>
</div>