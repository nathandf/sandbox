<?php
namespace Core;

use Conf\Config,
    Core\Http\Request;
class Router
{
    protected $routes = [];
    protected $params = []; // Parameters from the matched routes
    private $config;

    public function __construct( Config $config )
    {
        $this->config = $config;

        require_once( "App/Conf/routes.php" );

        foreach ( $routes as $route ) {
            $this->add( $route[ 0 ], $route[ 1 ] );
        }
    }

    // Add a route to the routing table
    public function add( $route, $params = [] )
    {
        // converting route to reg ex - escape forward slashes
        $route = preg_replace( '/\//', '\\/', $route );

        // converting variables e.g. {controller}
        $route = preg_replace( '/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route );

        // converting variables with custom regex
        $route = preg_replace( '/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route );

        // add start and end delimiters and case insensitive flag
        $route = '/^' . $route . '$/i';

        $this->routes[ $route ] = $params;
    }

    public function match( $url )
    {
        // return false if url contains "Action" to prevent bypassing
        // before and after methods
        if ( strpos( $url, "Action" ) || strpos( $url, "load" ) ) {
            
            return false;
        }

        // regular expression for fixed url format controller/action
        foreach ( $this->routes as $route => $params ) {
            if ( preg_match( $route, $url, $matches ) ) {

                // Get named capture group values
                foreach ( $matches as $key => $match) {
                    if ( is_string( $key ) ) {
                        $params[ $key ] = $match;
                    }
                }

                $this->params = $params;

                return true;
            }
        }

        return false;
    }

  // Distpatch method of class. Should probably be a class itself
    public function handle( Request $request )
    {
        $url = $request->queryString();
        $url = $this->removeQueryStringVariables( $url );
        $this->resetGETSuperGlobal( $url );

        // Set the relative root
        $root = ( $this->config->getEnv() == "production"
            ? $this->config->configs[ "routing" ][ "production" ][ "root" ]
            : $this->createRelativeURL( $url ) );

        define( "HOME", $root );
        
        if ( $this->match( $url ) ) {
            $request->setRoute( $this->params );
        }

        return $request;
    }
    
    // remove query string variable before matching
    protected function removeQueryStringVariables( $url )
    {
        if ( $url != '' ) {
            $parts = explode('&', $url, 2 );

            if ( strpos( $parts[ 0 ], '=' ) === false ) {
                $url = $parts[ 0 ];
            } else {
                $url = '';
            }
        }
        return $url;
    }

    protected function resetGETSuperGlobal( $url )
    {
        unset( $_GET[ $url ] );
    }

    protected function createRelativeURL( $url )
    {
        $root = "./";
        $depth = ( count( explode( "/", $url ) ) ) - 1;

        if ( $depth > 0 ) {
            $root = str_repeat( "../", $depth );
        }

        return $root;
    }
}
