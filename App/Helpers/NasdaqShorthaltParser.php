<?php

namespace Helpers;

class NasdaqShorthaltParser
{
	private $base_url = "https://www.nasdaqtrader.com/dynamic/symdir/shorthalts/";
	private $filename_base = "shorthalts";
	private $filename_extension = ".txt";
	private $delimiter = ",";
	private $data = [];

	public function parse( int $date )
	{
		if ( !$this->isValidDate( $date ) || $this->isWeekend( $date ) ) {
			return false;
		}

		// Build the url to be queried
        $url = $this->base_url . $this->filename_base . $date . $this->filename_extension;

        // Get the data from nasdaqtrader.com
		$contents = file_get_contents( $url );

        // Create an array of the returned data in which each element of the array is
        // a row of data. Each row ends in a newline char
        $rows = preg_split( "/\n/", $contents );

        // Remove all non-alphanumeric chars. If the element is empty,
        // then there is either no data for this day, or it is not a trading day
        if( preg_replace( "/[^a-zA-Z0-9]/", "", preg_replace( "/[\s]+/", "", $rows[ 0 ] ) ) == "" ) {
        	return false;
        }
        
        // Removes last empty element
        array_pop( $rows );
        
        // Removes timestamp elemnt
        array_pop( $rows );
        
        // Remove the column name string from data
        $column_name_string = array_shift( $rows );

        // Create an array of it's parsed and trimmed column names
        $column_names = array_map( "trim", explode( $this->delimiter, $column_name_string ) );
        
        // Parse the rows and create the data array
        foreach ( $rows as $row ) {
        	// Create an array of the rows
            $parsed_row = explode( $this->delimiter, $row );
            
            // Reformat data for names with commas. There should only be 4 elements.
            // The elements containing the parts of names will be 1 and 2.
            if ( count( $parsed_row ) == 5 ) {

                // Add the comma the append element at index 2
                $parsed_row[ 1 ] .= "," . $parsed_row[ 2 ];

                // Remove the quotes that surround the names with commas in them
                $parsed_row[ 1 ] = str_replace( "\"", "", $parsed_row[ 1 ] );

                // Remove element 2 from array
                unset( $parsed_row[ 2 ] );

                // Reindex the array
                $parsed_row = array_values( $parsed_row );
            }

            // Remove the \r char from Trigger Time
            $parsed_row[ 3 ] = str_replace( "\r", "", $parsed_row[ 3 ] );

            // Make the values of the column names array to be the new
            // keys of the parsed row array. Then add to data array.
            $this->data[] = array_combine( $column_names, $parsed_row );
        }

        return true;
	}

	public function getData()
	{
		return $this->data;
	}

	private function isValidDate( $date )
	{
		preg_match( "/^([0-9]{4})(0[1-9]|1[0-2])(0[1-9]|[1-2][0-9]|3[0-1])$/", $date, $matches );

		// Check that the date is formatted properly
		if ( !empty( $matches ) ) {

			// Check that the date resolves to a real date
			$dateTime = \DateTime::createFromFormat( "Ymd", $date );

			if ( $dateTime && ( $dateTime->format( "Ymd" ) == $date ) ) {
				return true;
			}

			return false;
		}

		return false;
	}

	private function isWeekend( $date )
	{
		// 'N' in date returns the ISO-8601 numeric representation of the day of the week
		// 1 = Monday -> 7 = Sunday
		return ( date( "N", strtotime( (string)$date ) ) >= 6 );
	}
}