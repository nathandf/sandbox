<?php

namespace Core;

class View 
{
    public $templateInheritenceResolver;

    public function __construct()
    {
        $this->templateInheritenceResolver = new \Core\TemplateInheritenceResolver;
    }

    public function renderTemplate( $filename, $data = [] )
    {
        if ( $this->templateInheritenceResolver->buildTemplate( $filename ) ) {

            ob_start();

            // Display the temp file
            require_once( $this->templateInheritenceResolver->getTempFile() );

            ob_flush();

            // Delete the tempfile
            $this->templateInheritenceResolver->removeTempFile();

            return;
        }
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

    public function renderErrorMessage( $message )
    {
        echo( "<div class='ble3 bsh br2 dt bc-red p10 bg-white hov-pointer --c-hide'>{$message}</div>" );
    }

    public function redirectWithData( $url, array $data )
    {
        if ( $url == "" ) {
            $url = HOME;
        }

        if ( !empty( $data ) ) {
            $url = $url . "?" . http_build_query( $data );
        }

        header( "Location: " . $url );
        exit();
    }

    public function redirect( $redirect_url, $http_response_code = 200 )
    {
        http_response_code( $http_response_code );
        header( "Location: " . $redirect_url );
        exit();
    }

    public function addErrorMessage( $index, $message )
    {
        $this->data[ "error_messages" ][ $index ] = $message;
    }

    public function stripQueryString( $url )
    {
        if ( strpos( $url, "?" ) ) {
            $url = explode( "?", $url )[ 0 ];
        }

        return $url;
    }
}
