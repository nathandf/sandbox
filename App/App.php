<?php

class App
{
	public function main()
	{
		// autoloading native classes and third party libraries. Check composer.json for details
		require_once( "App/vendor/autoload.php" );
		require_once( "App/Conf/configs/constants.php" );
		
		date_default_timezone_set( "UTC" );
		error_reporting( E_ALL );

		// Dependency injection container
		$containerFactory = new \Core\ContainerFactory( "App/Conf/" );
		$container = $containerFactory->build();

		// Load client requst
		$request = $container->getService( "request" );

		// Match the route
		$router = $container->getService( "router" );
		$request = $router->handle( $request );

		// Dispatch the request
		$dispatcher = $container->getService( "dispatcher" );
		
		$controller = $dispatcher->dispatch( $request );

		$controller->{$dispatcher->getActionMethod()}( $request->route( "id" ) );
	}
}