<?php

namespace Model\Services;

use Model\Entities\User;

class UserAggregateRepository
{
	private $accountRepo;
	private $userUiRepo;
	private $serviceRequestRepo;
	private $commitmentRequestRepo;
	private $aggregateFactory;
	private $service_request_count = 10;
	private $commitment_request_count = 10;
	private $user;

	public function __construct(
		AccountRepository $accountRepo,
		PhoneRepository $phoneRepo,
		UserUiRepository $userUiRepo,
		ServiceRequestRepository $serviceRequestRepo,
		CommitmentRequestRepository $commitmentRequestRepo,
		AggregateFactory $aggregateFactory
	) {
		$this->accountRepo = $accountRepo;
		$this->phoneRepo = $phoneRepo;
		$this->userUiRepo = $userUiRepo;
		$this->serviceRequestRepo = $serviceRequestRepo;
		$this->commitmentRequestRepo = $commitmentRequestRepo;
		$this->aggregateFactory = $aggregateFactory;
	}

	public function getAggregate( User $user, array $entity_id_strings = [] )
	{
		$entities = [];

		/**
		 * @todo Consider registering an anonymous function containing the logic for retrieving
		 * the required data in an array then calling the function from the array as needed
		 */
		// Use the provided id strings to call the methods and retrieve the related entities
		foreach ( $entity_id_strings as $id_string ) {
			$method = "get" . $this->idStringToEntityName( $id_string );
			$entities[ $this->idStringToPropertyName( $id_string ) ] = $this->$method( $user );
		}

		// Create the user aggregate.
		$userAggregate = $this->aggregateFactory->build( "User", $user );

		// Add the entities to the user aggregate
		$userAggregate->addEntities( $entities );

		return $userAggregate;
	}

	private function getAccount( $user )
	{
		return $this->accountRepo->select( "*" )
			->whereColumnValue( "id", "=", $user->current_account_id )
			->execute( "entity" );
	}

	private function getAccounts( $user )
	{
		return $this->accountRepo->select( "*" )
			->whereColumnValue( "id", "=", $user->id )
			->execute();
	}

	private function getPhone( $user )
	{
		return $this->phoneRepo->select( "*" )
			->whereColumnValue( "id", "=", $user->phone_id )
			->execute( "entity" );
	}

	private function getUserUi( $user )
	{
		return $this->userUiRepo->select( "*" )
			->whereColumnValue( "user_id", "=", $user->id )
			->execute( "entity" );
	}

	private function getServiceRequests( $user )
	{
		return $this->serviceRequestRepo->select( "*" )
			->whereColumnValue( "user_id", "=", $user->id )
			->orderBy( "created_at", "desc" )
			->limit( $this->getServiceRequestCount() )
			->execute();
	}

	private function getCommitmentRequests( $user )
	{
		return $this->commitmentRequestRepo->select( "*" )
			->whereColumnValue( "user_id", "=", $user->id )
			->orderBy( "created_at", "desc" )
			->limit( $this->getCommitmentRequestCount() )
			->execute();
	}

	public function setServiceRequestCount( $service_request_count )
	{
		if ( is_int( $service_request_count ) ) {
			if ( $service_request_count > 0 ) {
				$this->service_request_count = $service_request_count;

				return $this;
			}

			throw new \Exception( "service_request_count must be greater than 0" );
		}

		throw new Exception( "service_request_count must be (int)" );
	}

	private function getServiceRequestCount()
	{
		return $this->service_request_count;
	}

	public function setCommitmentRequestCount( $commitment_request_count )
	{
		if ( is_int( $commitment_request_count ) ) {
			if ( $commitment_request_count > 0 ) {
				$this->commitment_request_count = $commitment_request_count;

				return $this;
			}

			throw new \Exception( "commitment_request_count must be greater than 0" );
		}

		throw new Exception( "commitment_request_count must be (int)" );
	}

	private function getCommitmentRequestCount()
	{
		return $this->commitment_request_count;
	}

	private function idStringToEntityName( $id_string )
	{
		return str_replace( "-", "", ucwords( $id_string, "-" ) );
	}

	private function idStringToPropertyName( $id_string )
	{
		return lcfirst( $this->idStringToEntityName( $id_string ) );
	}
}