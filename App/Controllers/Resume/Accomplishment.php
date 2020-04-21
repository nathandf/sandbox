<?php

namespace Controllers\Resume;

use Core\BaseController;

class Accomplishment extends BaseController
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
        ppd( __CLASS__ . " index. Will require 'id'" );
    }

    public function create()
    {
        $requestValidator = $this->load( "request-validator" );

        if (
            $this->request->is( "post" ) &&
            $requestValidator->validate(
                $this->request,
                [
                    "csrf-token" => [
                        "required" => true
                    ],
                    "description" => [
                        "required" => true,
                        "max" => 256
                    ]
                ],
                "new-accomplishment"
            )
        ) {
            ppd( $this->request->post() );
        }
    }
}