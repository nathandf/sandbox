<?php

namespace Controllers;

use Core\BaseController;

class Home extends BaseController
{
    public function before()
    {
        $userAuthenticator = $this->load( "user-authenticator" );

        if ( !$userAuthenticator->user_authenticated ) {
            $view = $this->view( "User/Auth/SignIn" );
            $view->redirect( HOME . "sign-in" );
        }
    }

	public function indexAction()
	{
		$view = $this->view( "Home/Index" );
        $view->render();
	}

	public function signIn()
	{
        $view = $this->view( "Home/SignIn" );
        
        // Check for logged in user. If one exists, redirect to profile
		$userAuthenticator = $this->load( "user-authenticator" );
		if ( !is_null( $userAuthenticator->getAuthenticatedUser() ) ) {
            $view->redirect( HOME );
		}

        // Assign the csrf token value and sign in value
        $view->assign( "csrf-token", $this->request->csrf_token );
        $view->assign( "sign-in", $this->request->csrf_token );
        
        if ( $this->request->is( "get" ) ) {
            $dtoFactory = $this->load( "dto-factory" );
            $formData = $dtoFactory->build( "SignInFormData" );
            $formData->arrayToDTO( $this->request->get() );

            $view->assign( "formData", $formData );
        }

        $view->render();
	}
}