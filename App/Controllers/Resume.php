<?php

namespace Controllers;

use Core\BaseController;

class Resume extends BaseController
{
    public function before()
    {
        $userAuthenticator = $this->load( "user-authenticator" );

        if ( !$userAuthenticator->user_authenticated ) {
            $view = $this->view( "User/Auth" );
            $view->redirect( HOME . "sign-in" );
        }
    }

	public function indexAction()
	{
		$view = $this->view( "Resume/Index" );
        $view->render();
	}
}