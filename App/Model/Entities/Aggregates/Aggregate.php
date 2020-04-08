<?php

namespace Model\Entities\Aggregates;

use Contracts\EntityInterface;

abstract class Aggregate implements EntityInterface
{
	public function __construct( EntityInterface $entity )
	{
		$this->mapRootEntityPropertiesToAggregate( $entity );
	}

	// Creates properties based on the properties of the provided entity.
	protected function mapRootEntityPropertiesToAggregate( EntityInterface $entity )
	{
		$properties = get_object_vars( $entity );

		foreach ( $properties as $property => $value ) {
			$this->{$property} = $value;
		}
	}

	/**
	 * @todo Create a buildEntityMap method that creates an EntityMap value object in which
	 * the object properties are the class names of the entities
	 */
	// Map the array of entities to properties on the aggregate object where the key of the array is the
	// class name in camelCase
	public function addEntities( array $entities )
	{
		foreach ( $entities as $key => $entity ) {
			$this->validatePermittedEntity( $key );
			
			if ( is_array( $entity ) ) {
				if ( $this->validateEntityArray( $entity ) ) {
					$this->$key = $entity;
					continue;
				}
			}

			if ( $this->validateEntity( $entity ) ) {
				$this->$key = $entity;
				continue;
			}

			throw new \Exception( "Entity invalid" );
		}

		return $this;
	}

	// Adds names of entities to the permitted entities array.
	// If you attempt to add an entity with an name that is not in the array, an
	// exception will be thrown. 
	protected function allowEntities( $entity_names )
	{
		if ( is_array( $entity_names ) ) {
			foreach ( $entity_names as $entity_name ) {
				$this->permitted_entities[] = $entity_name;
			}

			return $this;
		}

		$this->permitted_entities[] = $entity_names;

		return $this;
	}

	// All objects passed as args must be of type Contracts\EntitityInterface
	protected function validateEntityArray( array $entities )
	{
		foreach ( $entities as $entity ) {
			if ( !$this->validateEntity( $entity ) ) {
				return false; 
			}
		}

		return true;
	}

	// All objects passed as args must be of type Contracts\EntitityInterface
	protected function validateEntity( $entity )
	{
		if ( $entity instanceof EntityInterface ) {
			return true;
		}

		return false;
	}

	protected function validatePermittedEntity( $entity_name )
	{
		if ( !property_exists( $this, $entity_name ) ) {
			throw new \Exception( "{$entity_name} is not a valid property of aggregate " . get_class( $this ) );
		}

		return;
	}
}