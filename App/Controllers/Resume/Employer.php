<?php

namespace Controllers\Resume;

use Core\BaseController;

class Employer extends BaseController
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
        $view = $this->view( "Resume/Employer/Index" );

        $view->render();
    }

    public function createAction()
    {
        $view = $this->view( "Resume/Employer/Create" );
        $requestValidator = $this->load( "request-validator" );

        if (
            $this->request->is( "post" ) &&
            $requestValidator->validate(
                $this->request,
                [
                    "csrf-token" => [
                        "required" => true
                    ],
                    "name" => [
                        "required" => true,
                        "max" => 128
                    ],
                    "city" => [
                        "required" => true,
                        "max" => 256
                    ],
                    "region" => [
                        "required" => true,
                        "max" => 256
                    ]
                ]
            )
        ) {
            $employerRepo = $this->load( "employer-repository" );
            $entityFactory = $this->load( "entity-factory" );

            $employer = $entityFactory->build( "Employer" );

            $employer->user_id = $this->user->id;
            $employer->name = $this->request->post( "name" );
            $employer->city = $this->request->post( "city" );
            $employer->region = $this->request->post( "region" );

            $employer = $employerRepo->persist( $employer );

            if ( $this->request->isAjax() ) {
                $view->respond()
                    ->setSuccess( true )
                    ->setHttpStatusCode( 201 )
                    ->setData( [ $employer ] )
                    ->send();
            }

            $view->back();
        }

        if ( $this->request->isAjax() ) {
            $view->respond()
                ->setSuccess( false )
                ->setHttpStatusCode( 422 )
                ->addMessage( $requestValidator->getError( 0 ) )
                ->send();
        }

        $view->backWithData( [ "error" => $requestValidator->getError( 0 ) ] );
    }

    public function deleteAction( $id = null )
    {
        if ( is_null( $id ) ) {
            $view = $this->view( "Resume/Employer/Delete" );
            $view->renderTemplate( "Errors/404.php" );
        }

        $view = $this->view( "Resume/Employer/Delete" );
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
            $employerRepo = $this->load( "employer-repository" ); 

            $employer = $employerRepo->select( "id" )
                ->whereColumnValue( "id", "=", $id )
                ->and()->columnValue( "user_id", "=", $this->user->id )
                ->execute();


            if ( is_null( $employer ) ) {
                if ( $this->request->isAjax() ) {
                    $view->respond()
                        ->setSuccess( false )
                        ->setHttpStatusCode( 404 )
                        ->addMessage( "Resource not found" )
                        ->send();
                }

                $view->backWithData( [ "error" => "Resource not found" ] );
            }

            $employerRepo->deleteEntity( $employer );

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