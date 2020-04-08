<?php

namespace Model\Entities\Aggregates;

use Contracts\EntityInterface,
	Model\Entities\ServiceRequest,
	Model\Entities\Account;

class User extends Aggregate
{
	public $account;
	public $accounts = [];
	public $phone;
	public $userUi;
	protected $permitted_entities = [];

	public function __construct( EntityInterface $user )
	{
		$this->mapRootEntityPropertiesToAggregate( $user );
		$this->allowEntities( [ "account", "accounts", "phone", "address", "addresses" ] );
	}
}