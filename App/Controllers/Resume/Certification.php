<?php

namespace Controllers\Resume;

use Core\BaseController;

class Certification extends BaseController
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
        $view = $this->view( "Resume/Certification/Create" );

        if (
            $this->request->is( "post" ) &&
            $requestValidator->validate(
                $this->request,
                [
                    "csrf-token" => [
                        "required" => true
                    ],
                    "name" => [
                        "required" => true
                    ],
                    "description" => [
                        "max" => 256
                    ],
                    "issued-by" => [
                        "max" => 64
                    ],
                    "date-awarded" => [
                        "required" => true
                    ]
                ]
            )
        ) {
            $entityFactory = $this->load( "entity-factory" );

            $certification = $entityFactory->build( "Certification" );

            $certification->user_id = $this->user->id;
            $certification->name = $this->request->post( "name" );
            $certification->description = $this->request->post( "description" );
            $certification->issued_by = $this->request->post( "issued-by" );
            $certification->date_awarded = $this->request->post( "date-awarded" );

            $certificationRepo = $this->load( "certification-repository" );
            $certification = $certificationRepo->persist( $certification );

            if ( $this->request->isAjax() ) {
                $view->response()
                    ->setHttpStatusCode( 201 )
                    ->setSuccess( true )
                    ->setData( [ $certification ] )
                    ->send();
            }

            $view->back();
        }

        if ( $this->request->isAjax() ) {               
            $view->response()
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
            $view = $this->view( "Resume/Certification/Delete" );
            $view->renderTemplate( "Errors/404.php" );
        }

        $view = $this->view( "Resume/Certification/Delete" );
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
            $certificationRepo = $this->load( "certification-repository" ); 

            $certification = $certificationRepo->select( "id" )
                ->whereColumnValue( "id", "=", $id )
                ->and()->columnValue( "user_id", "=", $this->user->id )
                ->execute();


            if ( is_null( $certification ) ) {
                if ( $this->request->isAjax() ) {
                    $view->response()
                        ->setSuccess( false )
                        ->setHttpStatusCode( 404 )
                        ->addMessage( "Resource not found" )
                        ->send();
                }

                $view->backWithData( [ "error" => "Resource not found" ] );
            }

            $certificationRepo->deleteEntity( $certification );

            if ( $this->request->isAjax() ) {
                $view->response()
                    ->setSuccess( true )
                    ->setHttpStatusCode( 204 )
                    ->addMessage( "Resource deleted successfully" )
                    ->send();
            }

            $view->back();
        }

        // response with json if request fails and is ajax
        if ( $this->request->isAjax() ) {
            $view->response()
                ->setSuccess( false )
                ->setHttpStatusCode( 422 )
                ->addMessage( $requestValidator->getError( 0 ) )
                ->send();
        }

        $view->backWithData( [ "error" => $requestValidator->getError( 0 ) ], true );
    }
}