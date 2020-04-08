<?php

namespace Core;

use Core\Http\Response;

abstract class ApiController extends BaseController
{
	private $http_errors = [
		400 => "Bad Request",
		401 => "Unauthorized",
		402 => "Payment Required",
		403 => "Forbidden",
		404 => "Not Found",
		405 => "Method Not Allowed",
		408 => "Request Timeout",
		410 => "Gone",
		413 => "Payload Too Large",
		415 => "Unsupported Media Type",
		418 => "I'm a teapot",
		422 => "Unprocessable Entity",
		429 => "Too Many Requests",
		431 => "Request Header Fields Too Large",
		500 => "Internal Server Error",
		501 => "Not Implemented",
		502 => "Bad Gateway",
		503 => "Service Unavailable",
		504 => "Gateway Timeout",
		505 => "HTTP Version Not Supported",
		510 => "Not Extended"
	];

	private $allowed_methods = [];

	public function __call( $name, $args )
	{
		try {
			parent::__call( $name, $args );
		} catch ( \Exception $e ) {
			$this->respondWithError( $e->getCode(), $e->getMessage() );
		}

		return;
	}
	
	public function allowMethods( $methods )
	{
		if ( is_array( $methods ) ) {
			if ( !in_array( strtolower( $this->request->method() ), $methods ) ) {
				$this->respondWithError( 405 );
			}

			return;
		}

		if ( strtolower( $this->request->method() ) != $methods ) {
			$this->respondWithError( 405 );
		}

		return;
	}

	public function respondWithError( $http_status_code, $message = null )
	{
		if ( !array_key_exists( $http_status_code, $this->http_errors ) ) {
			$http_status_code = 500;
		}

		if ( empty( $message ) ) {
			$message = $this->http_errors[ $http_status_code ];
		}

		$response = $this->buildResponse();
        $response->setHttpStatusCode( $http_status_code )
            ->addMessage( $message )
            ->setSuccess( false )
            ->send();
        exit;
	}

	protected function buildResponse()
	{
		$response = new Response;

		return $response;
	}
}
