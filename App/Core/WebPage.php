<?php

namespace Core;

class WebPage extends View
{
    public function loadComponent( $component, array $data = [] )
    {
        $components_dir = "App/Views/Components/";

        $component_file = $components_dir . $component . ".php";

        if ( file_exists( $component_file ) ) {

            //Create variables 
            foreach ( $data as $key => $value ) {
                $$key = $value;
            }

            include( $component_file );

            return;
        }

        $this->renderErrorMessage( "Component file not found: {$component_file}" );
    }
}
