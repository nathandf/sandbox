<?php

namespace Core;

class WebPage extends View
{
    public TemplateInheritenceResolver $templateInheritenceResolver;
    private int $loaded_component_count = 0;

    public function __construct()
    {
        parent::__construct();
        $this->templateInheritenceResolver = new \Core\TemplateInheritenceResolver;
    }

    public function loadComponent( $component, array $data = [] )
    {
        $components_dir = "App/Views/Components/";

        $component_file = $components_dir . $component . ".php";

        if ( file_exists( $component_file ) ) {

            // Iterate the loaded component count
            $this->loaded_component_count++;

            // Create variables for component from provided data
            foreach ( $data as $key => $value ) {
                $key = str_replace( "-", "_", $key );
                $$key = $value;
            }

            // Create a unique id attribute value for each component create based
            // on the current loaded component count
            $componentId = $this->loaded_component_count . "-" . uniqid();

            // Add the csrf_token token tho the variables
            $csrf_token = $this->getCSRFToken();

            include( $component_file );

            return;
        }

        $this->renderErrorMessage( "Component file not found: {$component_file}" );
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

    public function renderErrorMessage( $message )
    {
        echo( "<div class='ble3 bsh br2 dt bc-red p10 bg-white hov-pointer --c-hide'>{$message}</div>" );
    }
}
