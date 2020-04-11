<?php

namespace Controllers\Resume;

use Core\BaseController;

class Certifications extends BaseController
{
    private $user;

    public function before()
    {
        $userAuthenticator = $this->load( "user-authenticator" );

        if ( !$userAuthenticator->user_authenticated ) {
            $view = $this->view( "User/Auth" );
            $view->redirect( HOME . "sign-in" );
        }

        $this->user = $userAuthenticator->getAuthenticatedUser();
    }

    public function indexAction()
    {
        $view = $this->view( "Resume/Certifications/Index" );
        
        $certificationRepo = $this->load( "certification-repository" );
        $certificationList = $certificationRepo->select( "*" )
            ->whereColumnValue( "user_id", "=", $this->user->id )
            ->execute();

        $view->assign( "certificationList", $certificationList );

        $view->render();
    }
}