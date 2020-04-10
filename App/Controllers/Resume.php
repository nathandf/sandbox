<?php

namespace Controllers;

use Core\BaseController;

class Resume extends BaseController
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
		$view = $this->view( "Resume/Index" );
        
        $resumeRepo = $this->load( "resume-repository" );
        $resumes = $resumeRepo->select( "*" )
            ->whereColumnValue( "user_id", "=", $this->user->id )
            ->execute();

        $view->assign( "resumeList", $resumes );

        $view->render();
	}
}