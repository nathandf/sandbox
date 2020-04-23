<?php

namespace Core;

use Core\Http\Response,
    Core\TemplateInheritenceResolver;

class View 
{
    private Response $response;
    private $origin;
    private $referer;
    private $csrf_token;

    public function __construct()
    {
        $this->origin = null;
        $this->referer = null;
    }

    public function assign( $index, $data, $sanitize = true )
    {
        $this->data[ $index ] = $data;

        return $this;
    }

    public function getData( $index = null )
    {
        if ( !is_null( $index ) ) {
            if ( isset( $this->data[ $index ] ) ) {
                return $this->data[ $index ];
            }

            return;
        }

        return $this->data;
    }

    public function echoData( $index = null )
    {
        if ( !is_null( $index ) ) {
            echo( $this->getData( $index ) );

            return;
        }

        return null;
    }

    public function redirect( $redirect_url, $http_response_code = 200 )
    {
        http_response_code( $http_response_code );
        header( "Location: " . $redirect_url );
        exit();
    }

    public function redirectWithData( $url, array $data, $strip_query_string = false )
    {
        if ( $url == "" ) {
            $url = HOME;
        }

        if ( $strip_query_string ) {
            $url = $this->stripQueryString( $url );
        }

        if ( !empty( $data ) ) {
            $url = $url . "?" . http_build_query( $data );
        }

        header( "Location: " . $url );
        exit();
    }

    public function back( $strip_query_string = true ) : void
    {
        if ( $strip_query_string ) {
            $this->redirect( $this->stripQueryString( $this->getReferer() ) );
        }

        $this->redirect( $this->getReferer() );
    }

    public function backWithData( array $data, $strip_query_string = true ) : void
    {
        $this->redirectWithData( $this->getReferer(), $data, $strip_query_string );
    }

    public function addErrorMessage( $index, $message ) : View
    {
        $this->data[ "error_messages" ][ $index ] = $message;
        return $this;
    }

    public function stripQueryString( $url )
    {
        if ( strpos( $url, "?" ) ) {
            $url = explode( "?", $url )[ 0 ];
        }

        return $url;
    }

    public function setOrigin( $origin ) : View
    {
        $this->origin = $origin;
        return $this;
    }

    public function getOrigin() : ?string
    {
        return $this->origin;
    }

    public function setReferer( $referer ) : View
    {
        $this->referer = $referer;
        return $this;
    }

    public function getReferer() : ?string
    {
        return $this->referer;
    }

    public function setCSRFToken( $csrf_token ) : View
    {
        $this->csrf_token = $csrf_token;
        return $this;
    }

    public function getCSRFToken( $csrf_token ) : ?string
    {
        return $this->csrf_token;
    }
}
