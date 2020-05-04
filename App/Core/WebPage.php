<?php

namespace Core;

class WebPage extends View
{
    public TemplateInheritenceResolver $templateInheritenceResolver;
    private int $loaded_component_count = 0;
    private $components = [];
    private $scripts = [];
    private $title;
    private $description;

    public function __construct()
    {
        parent::__construct();
        $this->templateInheritenceResolver = new \Core\TemplateInheritenceResolver;
        $this->registerScript( HOME . "public/jqueryui/js/jquery.js" )
            ->registerScript( HOME . "public/jqueryui/js/jquery-ui.js" )
            ->registerScript( HOME . "public/jqueryui/js/jquery.ui.touch-punch.min.js" )
            ->registerScript( HOME . "public/js/rapid.js" );
    }

    public function loadComponent( string $component, array $data = [] ) : void
    {
        $components_dir = "App/Views/Components/";

        $component_file = $components_dir . $component . ".php";

        if ( file_exists( $component_file ) ) {

            // Create a unique id for each component
            $componentId = $this->loaded_component_count . "-" . uniqid();

            // Create a classes array that will be applied to each component
            $classes = [];

            // Iterate the loaded component count
            $this->loaded_component_count++;

            // Create variables for component from provided data
            foreach ( $data as $key => $value ) {
                $key = str_replace( "-", "_", $key );
                $$key = $value;
            }

            // Add the csrf_token token the the variables
            $csrf_token = $this->getCSRFToken();

            // Include the component file into the output buffer
            include( $component_file );

            // Register the component to the web page and load any js for the
            // component if it exists
            $this->registerComponent( $component );

            return;
        }

        $this->renderErrorMessage( "Component file not found: {$component_file}" );
    }

    public function renderTemplate( $filename, $data = [] )
    {
        // Create variables for template from provided data
        foreach ( $data as $key => $value ) {
            $key = str_replace( "-", "_", $key );
            $$key = $value;
        }

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

    public function renderErrorMessage( $message )
    {
        echo( "<div class='ble3 bsh br2 dt bc-red p10 bg-white hov-pointer --c-hide'>{$message}</div>" );
    }

    protected function registerComponent( string $component ) : void
    {
        // Check if the component is registered. If not, register both the component
        // and any related javascript files to the web page.
        if ( !$this->componentIsRegistered( $component ) ) {
            $this->registerComponentJs( $component );
            $this->components[] = $component;
        }

        return;
    }

    protected function registerScript( string $src ) : WebPage
    {
        $this->scripts[] = $src;
        return $this;
    }

    protected function registerComponentJs( string $component ) : void
    {
        $component_javascript_file = "public/Components/" . $component . ".js";

        // Check if any js files exist with the components name.
        if ( file_exists( $component_javascript_file ) ) {
            // Regsiter the file name
            $this->registerScript( HOME . $component_javascript_file );
        }

        return;
    }

    private function componentIsRegistered( string $component ) : bool
    {
        if ( in_array( $component, $this->components ) ) {
            return true;
        }

        return false;
    }

    public function getScriptTags() : string
    {
        $script_tags = "";
        foreach ( $this->scripts as $filename ) {
            $script_tags .= "<script src=\"{$filename}\"></script>\n";
        }

        return $script_tags;
    }

    public function renderScriptTags() : void
    {
        echo( $this->getScriptTags() );

        return;
    }
}
