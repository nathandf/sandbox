<?php

namespace Controllers\Resume;

use Core\BaseController;

class Experience extends BaseController
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
        $view = $this->view( "Resume/Experience/Index" );
        
        $experienceRepo = $this->load( "experience-repository" );
        $experienceList = $experienceRepo->select( "*" )
            ->whereColumnValue( "user_id", "=", $this->user->id )
            ->execute();

        $view->assign( "experienceList", $experienceList );

        $view->render();
    }
}