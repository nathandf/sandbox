<?php

namespace Model\DTOs;

use Contracts\IDTO;

abstract class DTO implements IDTO
{
	public function arrayToDTO( array $array, array $required_fields = [] )
	{
		$properties = get_object_vars( $this );

		foreach ( $properties as $property => $value ) {
			if ( in_array( $property, $required_fields ) && isset( $array[ $property ] ) === false ) {
				throw new \Exception( "Required field `{$property}` not found in array" );
			}

			// Save the value from array or null if not required
			$this->{$property} = ( isset( $array[ $property ] ) ? $array[ $property ] : null );
		}

		return;
	}

	public function DTOToArray()
	{
		$properties = get_object_vars( $this );

		$array = [];

		foreach ( $properties as $property => $value ) {
			if ( isset( $this->{$property} ) === false ) {
				$this->{$property} = null;
			}

			$array[ $property ] = $this->{$property};
		}

		return $array;
	}

	public function objectToDTO( $object, array $required_properties = []  )
	{
		if ( is_object( $object ) === false ) {
			throw new \Exception( "Argument 'object' must be an object" );
		}

		$dto_properties = get_object_vars( $this );
		$object_properties = get_object_vars( $object );

		foreach ( $dto_properties as $property => $value ) {
			if ( isset( $object->{$property} ) === false ) {
				throw new \Exception( "Property `{$property}` not found on object" );
			}

			$this->{$property} = $object->{$property};
		}

		return;
	}
}