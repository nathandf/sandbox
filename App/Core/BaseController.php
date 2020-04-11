<?php

namespace Core;

use Core\Http\Request;

abstract class BaseController extends CoreObject
{
    protected $container;
    protected $config;
    protected $request;
    protected $params;
    protected $viewData = [];

    public function __construct( Request $request, DIContainer $container )
    {
        $this->setContainer( $container );
        $this->config = $this->container->getService( "config" );
        $this->request = $request;
        $this->params = $request->route();
    }

    // Every time a method is called on Controller class, check if before and after
    // methods exist and run them respectively
    public function __call( $name, $args )
    {
        $method = $name . "Action";

        if ( method_exists( $this, $method ) ) {
            
            // Run the before method
            $this->before( ...$args );

            $this->$method( ...$args );

            // Run the after method
            $this->after( ...$args );

            return;
        }

        $view = $this->view( "Errors/Error" );
        $view->renderHttpErrorCode( 404 );
    }

    // Remove "Action" from the end of the method names on which you don't want the
    // before or action methods to be invoked automatically
    protected function before()
    {}

    protected function after()
    {}

    protected function requireParam( $param )
    {
        if ( !isset( $this->params[ $param ] ) ) {
            $this->view->render404();
            exit();
        }
    }

    protected function issetParam( $param )
    {
        if ( !isset( $this->params[ $param ] ) ) {
            return false;
        }
        
        return true;
    }

    protected function view( $name )
    {
        $view = "\\Views\\{$this->formatViewName($name)}";
        
        return new $view;
    }

    private function formatViewName( $name )
    {  
        return str_replace( "/", "\\", $name );
    }
}
