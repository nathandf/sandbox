<?php

namespace Core;

use Core\Http\Request,
	Core\BaseController;

class Dispatcher
{
	private $container;
	private $action_method;
	
	public function __construct( DIContainer $container )
	{
		$this->container = $container;
	}

	public function dispatch( Request $request )
	{
		// If params are not set, then no route was found
		if ( !empty( $request->route ) ) {

			// checking to see a a "path" regex variable was created by the router.
	        // uses the regex variable "path" to construct the path to the controller
	        // and create the namespace under which the controller exists
	        $namespace = "Controllers\\";

	        if ( !is_null( $request->route( "path" ) ) ) {
	            $namespace = $this->buildNamespace( $request->route( "path" ) );
	        }

			// Create the controller name
			$controller_name = $namespace . $this->formatClassName( $request->route( "controller" ) );

	        if ( class_exists( $controller_name ) ) {
	        	
	            if ( !is_null( $request->route( "action" ) ) ) {

					$method = $this->formatMethodName( $request->route( "action" ) );

					if ( $method == "" ) {
						$method = "index";
					}

					$this->setActionMethod( $method );

					$controller = new $controller_name( $request, $this->container );

					// Check to see if the controller has either the method specified in the method var
					// or the method var suffixed with 'Action'.
					if ( !method_exists( $controller, $method ) && !method_exists( $controller, $method . "Action" ) ) {
						$this->setActionMethod( "render404" );
					}
					
					return $controller;
				}

				throw new \Exception( "Action not set", 404 );
            }

			throw new \Exception( "Class \"$controller_name\" does not exist", 404 );
		}
		
		throw new \Exception( "No Route Matched", 404 );	
	}

	private function setActionMethod( $method )
	{
		$this->action_method = $method;
		return $this;
	}

	public function getActionMethod()
	{
		return $this->action_method;
	}

	protected function formatClassName( $string )
    {
        return str_replace( ' ', '', ucwords( str_replace( '-', ' ', $string ) ) );
    }

    protected function formatMethodName( $string )
    {
        return lcfirst( $this->formatClassName( $string ) );
    }

    private function buildNamespace( $path )
    {
		$namespace = "Controllers\\";

		$path_parts = explode( "/", $path );

		foreach ( $path_parts as $part ) {
			$namespace .= $this->formatClassName( $part ) . "\\";
		}

		return $namespace;
    }
}