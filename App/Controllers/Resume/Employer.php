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
    
    public function indexAction( $id = null )
    {
        if ( is_null( $id ) ) {
            if ( $this->request->isAjax() ) {
                $view = $this->view( "Errors/Error" );
                $view->response()
                    ->setSuccess( false )
                    ->setHttpStatusCode( 400 )
                    ->addMessage( "Resource id cannot be null" )
                    ->send();
            }

            $view = $this->view( "Errors/Error" );
            $view->renderHttpErrorCode( 404 );
        }

        $view = $this->view( "Resume/Employer/Index" );

        $employerRepo = $this->load( "employer-repository" );
        $employer = $employerRepo->select( "*" )
            ->whereColumnValue( "id", "=", $id )
            ->and()->columnValue( "user_id", "=", $this->user->id )
            ->execute( "entity" );

        if ( is_null( $employer ) ) {
            $view = $this->view( "Errors/Error" );

            if ( $this->request->isAjax() ) {
                $view->response()
                    ->setSuccess( false )
                    ->setHttpStatusCode( 404 )
                    ->addMessage( "Resource not found" )
                    ->send();
            }
            
            $view->renderHttpErrorCode( 404 );
        }
        
        $view = $this->view( "Resume/Employer/Index" );

        $view->assign( "employer", $employer );

        $positionRepo = $this->load( "position-repository" );
        $positionList = $positionRepo->select( "*" )
            ->whereColumnValue( "user_id", "=", $this->user->id )
            ->and()->columnValue( "employer_id", "=", $employer->id )
            ->execute();

        $dutyRepo = $this->load( "duty-repository" );
        foreach ( $positionList as $position ) {
            $position->dutyList = $dutyRepo->select( "*" )
                ->whereColumnValue( "position_id", "=", $position->id )
                ->execute();
        }

        $view->assign( "positionList", $positionList );

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
            $entityFactory = $this->load( "entity-factory" );

            $employer = $entityFactory->build( "Employer" );

            $employer->user_id = $this->user->id;
            $employer->name = $this->request->post( "name" );
            $employer->city = $this->request->post( "city" );
            $employer->region = $this->request->post( "region" );

            $employerRepo = $this->load( "employer-repository" );
            $employer = $employerRepo->persist( $employer );

            if ( $this->request->isAjax() ) {
                $view->response()
                    ->setSuccess( true )
                    ->setHttpStatusCode( 201 )
                    ->setData( [ $employer ] )
                    ->send();
            }

            $view->back();
        }

        if ( $this->request->isAjax() ) {
            $view->response()
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
                    $view->response()
                        ->setSuccess( false )
                        ->setHttpStatusCode( 404 )
                        ->addMessage( "Resource not found" )
                        ->send();
                }

                $view->backWithData( [ "error" => "Resource not found" ] );
            }

            $employerRepo->deleteEntity( $employer );

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