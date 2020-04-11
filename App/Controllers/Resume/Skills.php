<?php

namespace Controllers\Resume;

use Core\BaseController;

class Skills extends BaseController
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
        $view = $this->view( "Resume/Skills/Index" );
        
        $skillRepo = $this->load( "skill-repository" );
        $skillList = $skillRepo->select( "*" )
            ->whereColumnValue( "user_id", "=", $this->user->id )
            ->execute();

        $view->assign( "skillList", $skillList );

        $view->render();
    }
}