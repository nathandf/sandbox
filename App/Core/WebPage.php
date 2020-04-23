<?php

namespace Core;

class WebPage extends View
{
    public TemplateInheritenceResolver $templateInheritenceResolver;

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

            // Create variables 
            foreach ( $data as $key => $value ) {
                $key = str_replace( "-", "_", $key );
                $$key = $value;
            }

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
