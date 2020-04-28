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

    public function deleteAction( $id = null )
    {
        if ( is_null( $id ) ) {
            $view = $this->view( "Resume/Skill/Delete" );
            $view->renderTemplate( "Errors/404.php" );
        }

        $view = $this->view( "Resume/Skill/Delete" );
        $requestValidator = $this->load( "request-validator" );

        if (
            $this->request->is( "post" ) &&
            $requestValidator->validate(
                $this->request,
                [
                    "csrf-token" => [
                        "required" => true
                    ]
                ]
            )
        ) {
            $skillRepo = $this->load( "skill-repository" ); 

            $skill = $skillRepo->select( "id" )
                ->whereColumnValue( "id", "=", $id )
                ->and()->columnValue( "user_id", "=", $this->user->id )
                ->execute();


            if ( is_null( $skill ) ) {
                if ( $this->request->isAjax() ) {
                    $view->respond()
                        ->setSuccess( false )
                        ->setHttpStatusCode( 404 )
                        ->addMessage( "Resource not found" )
                        ->send();
                }

                $view->backWithData( [ "error" => "Resource not found" ] );
            }

            $skillRepo->deleteEntity( $skill );

            if ( $this->request->isAjax() ) {
                $view->respond()
                    ->setSuccess( true )
                    ->setHttpStatusCode( 204 )
                    ->addMessage( "Resource deleted successfully" )
                    ->send();
            }

            $view->back();
        }

        // Respond with json if request fails and is ajax
        if ( $this->request->isAjax() ) {
            $view->respond()
                ->setSuccess( false )
                ->setHttpStatusCode( 422 )
                ->addMessage( $requestValidator->getError( 0 ) )
                ->send();
        }

        $view->backWithData( [ "error" => $requestValidator->getError( 0 ) ], true );
    }
}