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

    public function createAction()
    {
        $requestValidator = $this->load( "request-validator" );
        $view = $this->view( "Resume/Accomplishment/Create" );

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
                ]
            )
        ) {
            $accomplishmentRepo = $this->load( "accomplishment-repository" );
            $entityFactory = $this->load( "entity-factory" );

            $accomplishment = $entityFactory->build( "Accomplishment" );

            $accomplishment->user_id = $this->user->id;
            $accomplishment->description = $this->request->post( "description" );

            $accomplishment = $accomplishmentRepo->persist( $accomplishment );

            if ( $this->request->isAjax() ) {
                $view->respond()
                    ->setHttpStatusCode( 201 )
                    ->setSuccess( true )
                    ->setData( [ $accomplishment ] )
                    ->send();
            }

            $view->back();
        }

        if ( $this->request->isAjax() ) {               
            $view->respond()
                ->setHttpStatusCode( 422 )
                ->setSuccess( false )
                ->addMessage( $requestValidator->getError( 0 ) )
                ->send();
        }

        $view->backWithData( [ "error" => $requestValidator->getError( 0 ) ] );
    }
}