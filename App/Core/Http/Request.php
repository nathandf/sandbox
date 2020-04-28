<?php

namespace Core\Http;

class Request
{
	private $request_types = [ "get", "post", "put", "patch", "delete" ];
	private $origin_whitelist = [];
	private $crsf_token;
    public $flash_messages = [];
    public $route = [];

	public function __construct()
    {
        // session
        if ( session_status() == PHP_SESSION_NONE ) {
            session_start();
			if ( is_null( $this->session( "csrf-token" ) ) ) {
				$this->setSession( "csrf-token", $this->generateCSRFToken() );
			}
        }

		$this->csrf_token = $this->session( "csrf-token" );
    }

	public function is( $request_type )
	{
		if ( !in_array( $request_type, $this->request_types ) ) {
			throw new \Exception( "Invalid request types" );
		}

		if ( strtolower( $this->method() ) == $request_type ) {
			return true;
		}

		return false;
	}

	public function server( $index )
	{
		if ( isset( $_SERVER[ $index ] ) ) {
			return $_SERVER[ $index ];
		}

		return null;
	}

	public function getContentType()
	{
		if ( isset( $_SERVER[ "CONTENT_TYPE" ] ) ) {
			return $_SERVER[ "CONTENT_TYPE" ];
		}
		
		return null; 
	}

	public function isAjax() : bool
	{
		if (
			!empty( $_SERVER[ "HTTP_X_REQUESTED_WITH" ] ) &&
			strtolower( $_SERVER[ "HTTP_X_REQUESTED_WITH" ] ) == "xmlhttprequest"
		) {
			return true;
		}

		return false;
	}

	public function get( $key = null )
	{
		if ( isset( $_GET ) ) {
			if ( !is_null( $key ) ) {
				if ( isset( $_GET[ $key ] ) ) {
					return $_GET[ $key ];
				}

				return "";
			}

			return $_GET;
		}

		return null;
	}

	public function post( $key = null )
	{
		if ( isset( $_POST ) ) {
			if ( !is_null( $key ) ) {
				if ( isset( $_POST[ $key ] ) ) {
					return $_POST[ $key ];
				}

				return "";
			}

			return $_POST;
		}

		return null;
	}

	public function method()
	{
		return filter_var( $_SERVER[ "REQUEST_METHOD" ], FILTER_UNSAFE_RAW );
	}

	public function queryString()
	{
		return filter_var( $_SERVER[ "QUERY_STRING" ], FILTER_UNSAFE_RAW );
	}

	public function ip()
	{
		return filter_var( $_SERVER[ "REMOTE_ADDR" ], FILTER_UNSAFE_RAW );
	}

	public function setRoute( $route )
	{
		$this->route = $route;
		return $this;
	}

	public function route( $key = null )
	{
		if ( !empty( $this->route ) ) {
			if ( !is_null( $key ) ) {
				if ( isset( $this->route[ $key ] ) ) {
					return $this->route[ $key ];
				}
			}
		}

		return null;
	}

	// TODO validate that origins are valid URLs
	public function populateWhitelist( $origins )
	{
		if ( is_array( $origins ) ) {
			foreach ( $origins as $origin ) {
				$this->origin_whitelist[] = $origin;
			}

			return;
		}

		$this->origin_whitelist[] = $origins;

		return;
	}

	public function allowOrigin( $origin )
	{
		if ( in_array( $origin, $this->origin_whitelist ) ) {
			header( "Access-Control-Allow-Origin: " . $origin );

			return true;
		}

		return false;
	}

	public function getOrigin()
	{
		if ( array_key_exists( "HTTP_ORIGIN", $_SERVER ) ) {
			$origin = $_SERVER[ "HTTP_ORIGIN" ];
		} elseif ( array_key_exists( "HTTP_REFERER", $_SERVER ) ) {
			$origin = $_SERVER[ "HTTP_REFERER" ];
		} else {
			$origin = $_SERVER[ "REMOTE_ADDR" ];
		}

		return $origin;
	}

	public function getReferer()
	{
		if ( array_key_exists( "HTTP_REFERER", $_SERVER ) ) {
			return $_SERVER[ "HTTP_REFERER" ];
		}

		return $origin = $_SERVER[ "REMOTE_ADDR" ];
	}

	// Session functions
	public function setSession( $index, $value )
    {
        $_SESSION[ $index ] = $value;
    }

    public function session( $index )
    {
        if ( isset( $_SESSION[ $index ] ) ) {
            return $_SESSION[ $index ];
        }

        return null;
    }

    public function addFlashMessage( $type, $message )
    {
		$flash_message_types = [ "error", "alert", "notification", "success" ];

		if ( !in_array( $type, $flash_message_types ) ) {
			throw new \Exception( "Provided 'flash message type' is invalid. Provided type: '{$type}' | Valid types: " . implode( ", ", $flash_message_types ) );
		}

		$this->flash_messages[ $type ][] = $message;
    }

    public function setFlashMessages()
    {
        $this->setSession( "flash_messages", json_encode( $this->flash_messages ) );
    }

    public function getFlashMessages()
    {
        if ( isset( $_SESSION[ "flash_messages" ] ) ) {
            $flash_messages = $_SESSION[ "flash_messages"];
            unset( $_SESSION[ "flash_messages" ] );

			// Decode json flash messages into an associative array
            return json_decode( $flash_messages, true );
        }

        return [];
    }

    public function generateCSRFToken()
    {
        $csrf_token = $this->generateToken();
        $this->setSession( "csrf-token", $csrf_token );

        return $csrf_token;
    }

    public function getCSRFToken()
    {
        return $this->crsf_token;
    }

    public function setCookie( $index, $value, $time = 86400 )
    {
        setcookie( $index, $value, time() + $time, "/" );
    }

    public function deleteCookie( $index )
    {
        setcookie( $index, null, time() - 3600, "/" );
    }

	public function cookie( $index )
    {
        if ( isset( $_COOKIE[ $index ] ) ) {
            return $_COOKIE[ $index ];
        }

        return null;
    }

    public function generateToken()
    {
        return hash( "md5", base64_encode( openssl_random_pseudo_bytes( 32 ) ) );
    }
}
