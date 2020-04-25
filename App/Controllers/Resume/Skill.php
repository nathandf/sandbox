<?php

namespace Controllers\Resume;

use Core\BaseController;

class Skill extends BaseController
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
        ppd( __CLASS__ );
    }

    public function createAction()
    {
        $requestValidator = $this->load( "request-validator" );
        $view = $this->view( "Resume/Skill/Create" );

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
            $skillRepo = $this->load( "skill-repository" );
            $entityFactory = $this->load( "entity-factory" );

            $skill = $entityFactory->build( "Skill" );

            $skill->user_id = $this->user->id;
            $skill->description = $this->request->post( "description" );

            $skill = $skillRepo->persist( $skill );

            if ( $this->request->isAjax() ) {
                $view->respond()
                    ->setHttpStatusCode( 201 )
                    ->setSuccess( true )
                    ->setData( [ $skill ] )
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