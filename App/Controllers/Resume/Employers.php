<?php

namespace Controllers\Resume;

use Core\BaseController;

class Employers extends BaseController
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
        $view = $this->view( "Resume/Employers/Index" );

        $employerRepo = $this->load( "employer-repository" );
        $employerList = $employerRepo->select( "*" )
            ->whereColumnValue( "user_id", "=", $this->user->id )
            ->execute();

        $view->assign( "employerList", $employerList );

        $view->render();
    }
}