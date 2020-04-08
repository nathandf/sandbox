<?php

namespace Controllers;

use \Core\BaseController;

class Test extends BaseController
{
    public function indexAction()
    {   
        $entities = [ "accomplishment", "account", "address",
        "certification", "country", "cover-letter", "education", "employer",
        "experience", "file", "image", "phone", "reference", "resume",
        "skill", "user", "video" ];

        $repos = [];

        foreach ( $entities as $entity ) {
        	$repos[] = $this->load( $entity . "-repository" );
        }

        ppd( $repos );
    }
}
