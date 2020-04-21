<?php

namespace Controllers\User;

use Core\BaseController;

class Auth extends BaseController
{
    public function signIn()
    {
        $view = $this->view( "User/Auth/SignIn" );

        if ( !$this->request->is( "post" ) ) {
            $view->redirect( HOME );
        }

        $requestValidator = $this->load( "request-validator" );
        
        if (
            $requestValidator->validate(
                $this->request,
                [
                    "csrf-token" => [
                        "required" => true,
                        "equals-hidden" => $this->request->session( "csrf-token" )
                    ],
                    "sign-in" => [
                        "required" => true
                    ],
                    "email" => [
                        "required" => true,
                        "email" => true
                    ],
                    "password" => [
                        "required" => true
                    ]
                ],
                "sign-in"
            )
        ) {
            $userAuth = $this->load( "user-authenticator" );

            if (
                $userAuth->authenticate(
                    $this->request->post( "email" ),
                    $this->request->post( "password" )
                )
            ) {
                $view->redirect( HOME );
            }

            $data = [];
            $data[ "error" ] = "Email or password is incorrect";
            $data = array_merge( $data, $this->request->post() );

            unset( $data[ "password" ] );
            unset( $data[ "csrf_token" ] );
            unset( $data[ "sign_in" ] );

            $view->redirectWithData( HOME . "sign-in", $data );
        }

        $data = [];
        $data[ "error" ] = $requestValidator->getErrors()[ "sign-in" ][ 0 ];
        $data = array_merge( $data, $this->request->post() );

        unset( $data[ "password" ] );
        unset( $data[ "csrf_token" ] );
        unset( $data[ "sign_in" ] );

        $view->redirectWithData(  HOME . "sign-in", $data );
    }

    public function logout()
    {
        $userAuthenticator = $this->load( "user-authenticator" );
        $userAuthenticator->logout();

        $view = $this->view( "User/Auth/Logout" );
        $view->redirect( HOME . "sign-in" );
    }
}