<?php

namespace Core\Http;

class Response
{
	private $httpStatusCode;
	private $success;
	private $messages = [];
	private $data;
	private $toCache = false;
	private $responseData = [];
	private $headers = [
		"Content-type: application/json;charset=utf-8",
		"Cache-control: no-cache, no-store"
	];

	public function setSuccess( $success )  
	{
		if ( !is_bool( $success ) ) {
			throw new \Exception( "Success must be of type bool" );
		}
		
		$this->success = $success;

		return $this;
	}

	public function setHttpStatusCode( $code )
	{
		if ( !is_numeric( $code ) ) {
			throw new \Exception( "Http status code must be numeric" );	
		}

		$this->httpStatusCode = $code;
		
		return $this;
	}

	public function addMessage( $message )
    {
    	if ( !is_string( $message ) ) {
    		throw new \Exception( "Message must be of type string" );
    	}

		$this->messages[] = $message;

		return $this;
	}

	public function setData( array $data )
    {
		$this->data = $data;

		return $this;
	}

	public function header( $header )
	{
		$this->headers[] = $header;
		return $this;
	}

	public function send()
	{
		foreach ( $this->headers as $header ) {
			header( $header );
		}

		// Ensure success and statusCode have the correct type
		if ( !is_bool( $this->success ) || !is_numeric( $this->httpStatusCode ) ) {
			$this->setHttpStatusCode( 500 );
			$this->addMesssage( "Response creation error" );
			$this->setSuccess( false );
		}

		// Send http response code to the client
		http_response_code( $this->httpStatusCode );

		// Build up the response data
		$this->responseData = [
			"statusCode" => $this->httpStatusCode,
			"success" => $this->success,
			"messages" => $this->messages,
			"data" => $this->data
		];

		// Echo the reponse data to the client
		echo( json_encode( $this->responseData, JSON_PRETTY_PRINT ) );
		exit;
	}

	public function respondWithError( $code, $additional_message = null )
	{
		$message = "";
		switch ( $code ) {
			case 400:
				$message = "Bad Request";
				break;

			case 403:
				$message = "Access Forbidden";
				break;

			case 404:
				$message = "Resource could not be found";
				break;

			case 422:
				$message = "Data required to retrieve this resource is either missing or incorrect";
				break;
			
			case 500:
				$message = "Server Error";
				break;

			default:
				$message = "An unknown error has occured";
				break;
		}

		$this->setHttpStatusCode( $code );
		$this->setSuccess( false );

		$this->addMessage( $message );
		if ( !is_null( $additional_message ) ) {
			$this->addMessage( $additional_message );
		}

		$this->send();
	}
}
