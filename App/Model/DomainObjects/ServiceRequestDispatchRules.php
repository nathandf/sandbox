<?php

namespace Model\DomainObjects;

use Contracts\DomainObjectInterface;

class ServiceRequestDispatchRules implements DomainObjectInterface
{
	const ACTIVE_DISPATCH_PROCESS_TYPE = 1;
	const USER_AGGREGATION_LIMIT = 5;
	const PRIORITY_FAILURE_LIMIT = 10;
	const SERVICE_REQUEST_LIFETIME = 300; // in seconds
}