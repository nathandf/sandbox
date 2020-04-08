<?php

namespace Helpers;

class ArrayHelper
{
	// Return the value of multidimensional arrays at the specified indices 
	public function getArrayValue( array $array, $indicies )
    {
       	switch ( gettype( $indicies ) ) {

       		case "string":
       			if ( array_key_exists( $indicies, $array ) ) {
       				return $array[ $indicies ];
       			}

       			return "Array key '{$indicies}' do not exist";

       		case "array":
       			// If indicies is empty, just return the array
       			if ( empty( $indicies ) ) {
		        	return $array;
		        }

		        if ( !array_key_exists( $indicies[ 0 ], $array ) ) {
		        	throw new \Exception( "Array key '{$indicies[ 0 ]}' does not exist" );
		        }

		        // If there is only one index, return the value of the array at that index
		        if ( count( $indicies ) == 1 ) {
		        	return $array[ $indicies[ 0 ] ];
		        }

		        // If more than one index exists create a new array from 
		        $new_array = $array[ $indicies[ 0 ] ];
		        array_shift( $indicies );

		        return $this->getArrayValue( $new_array, $indicies );

       		default:
       			throw new \Exception( "Argument 'indicies' must be of type 'string' or 'array'" );
       	}
    }
}