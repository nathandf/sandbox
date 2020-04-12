<?php

namespace Views\Resume\Experience;

use Core\WebPage;

class Index extends WebPage
{
	private $numMonth = [
		1 => "Jan", 2 => "Feb", 3 => "Mar", 4 => "Apr",
		5 => "May", 6 => "Jun", 7 => "Jul", 8 => "Aug",
		9 => "Sep", 10 => "Oct", 11 => "Nov", 12 => "Dec"
	];

	public function render()
	{
		return $this->renderTemplate( "Resume/Experience/Index.php" );
	}

	public function numToMonth( $num )
	{
		if ( is_numeric( $num ) ) {
			$num_int = ( int )$num;
			if ( array_key_exists( $num_int , $this->numMonth ) ) {
				return $this->numMonth[ $num_int ];
			}

			throw new \Exception( "Invalid value for 'num' argument" );
		}

		throw new \Exception( "Argument provided must be numeric" );
	}
}