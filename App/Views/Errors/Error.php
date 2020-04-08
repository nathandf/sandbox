<?php

namespace Views\Errors;

use Core\View;

class Error extends View
{
	private $http_error_codes = [ 301, 302, 403, 404, 500 ];

	public function render()
	{
		return;
	}

	public function renderHttpErrorCode( $code )
	{
		if ( in_array( $code, $this->http_error_codes ) ) {
			
			include( "App/Views/Templates/Errors/" . ( string )$code . ".php" );
			exit;
		}

		throw new \InvalidArgumentException( "Code provided must be one of the following values: [" . implode( ",", $this->http_error_codes ) );
	}
}
