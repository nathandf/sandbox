<?php

namespace Model\DomainObjects;

use Contracts\DomainObjectInterface;
use Model\Entities\ServiceRequest;

class ServiceRequestBatch implements DomainObjectInterface
{
	public $serviceRequests = [];
	public $has_volunteer_service_request = false;
	public $has_professional_service_request = false;
	
	public function register( array $serviceRequests )
	{
		if ( empty( $serviceRequests ) ) {
			return;
		}

		foreach ( $serviceRequests as $serviceRequest ) {

			// If serviceRequest in array not an instance of Model\Entity\ServiceRequest,
			// do nothing with it.
			if ( $this->validate( $serviceRequest ) ) {
				
				// Set has_<request_type>_request properties
				if (
					$this->has_volunteer_service_request === false &&
					$serviceRequest->service_request_type_id == 1
				) {
					$this->has_volunteer_service_request = true;
				}

				if (
					$this->has_professional_service_request === false &&
					$serviceRequest->service_request_type_id == 2
				) {
					$this->has_professional_service_request = true;
				}

				$this->serviceRequests[] = $serviceRequest;
			}
		}

		return;
	}

	private function validate( $serviceRequest )
	{
		if ( $serviceRequest instanceof ServiceRequest ) {
			return true;
		}

		return false;
	}

	private function getCount()
	{
		return count( $this->serviceRequests );
	}
}