<?php

namespace Controllers\Resume;

use Core\BaseController;

class Accomplishments extends BaseController
{
    private $user;

    public function before()
    {
        $userAuthenticator = $this->load( "user-authenticator" );

        if ( !$userAuthenticator->user_authenticated ) {
            $view = $this->view( "User/Auth/SignIn" );
            $view->redirect( HOME . "sign-in" );
        }

        $this->user = $userAuthenticator->getAuthenticatedUser();
    }

    public function indexAction()
    {
        $view = $this->view( "Resume/Accomplishments/Index" );
        
        $accomplishmentRepo = $this->load( "accomplishment-repository" );
        $accomplishmentList = $accomplishmentRepo->select( "*" )
            ->whereColumnValue( "user_id", "=", $this->user->id )
            ->execute();

        $view->assign( "accomplishmentList", $accomplishmentList );

        $view->render();
    }
}