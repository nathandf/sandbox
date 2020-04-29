<?php

namespace Controllers\Resume;

use Core\BaseController;

class Position extends BaseController
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
        $view = $this->view( "Resume/Position/Index" );
        
        $positionRepo = $this->load( "position-repository" );
        $positions = $positionRepo->select( "*" )
            ->whereColumnValue( "user_id", "=", $this->user->id )
            ->execute();

        $positionList = [];

        // Get all employers and duty entities related to each position
        $employerRepo = $this->load( "employer-repository" );
        $employerList = $employerRepo->select( "*" )
            ->whereColumnValue( "user_id", "=", $this->user->id )
            ->execute();

        $view->assign( "employerList", $employerList );

        $dutyRepo = $this->load( "duty-repository" );
        
        foreach ( $positions as $position ) {

            // Employer
            $position->employer = $employerRepo->select( "*" )
                ->whereColumnValue( "id", "=", $position->employer_id )
                ->execute( "entity" );

            // Duties
            $position->dutyList = $dutyRepo->select( "*" )
                ->whereColumnValue( "position_id", "=", $position->id )
                ->execute();

            $positionList[] = $position;
        }

        $view->assign( "positionList", $positionList );

        $view->render();
    }

    public function createAction()
    {
        $view = $this->view( "Resume/Position/Create" );
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
                        "max" => 256
                    ],
                    "start-month" => [
                        "required" => true,
                        "max" => 256
                    ],
                    "end-month" => [
                        "required" => true,
                        "max" => 256
                    ],
                    "currently-employed" => [],
                    "month-graduated" => [],
                    "year-graduated" => []
                ]
            )
        ) {
            $entityFactory = $this->load( "entity-factory" );

            $position = $entityFactory->build( "Position" );
            $position->user_id = $this->user->id;
            $position->name = $this->request->post( "name" );
            $position->start_month = $this->request->post( "start-month" );
            $position->start_year = $this->request->post( "start-year" );
            $position->currently_employed = $this->request->post( "currently-employed" );
            
            if ( $position->currently_employed != "" ) {
                if ( $position->end_month == "" || $position->end_year == "" ) {
                    $error_message = "End month and year must be set if not currently employed in this position";
                    if ( $this->request->isAjax() ) {
                        $view->respond()
                            ->setSuccess( false )
                            ->setHttpStatusCode( 422 )
                            ->addMessage( $error_message )
                            ->send();
                    }

                    $view->backWithData( [ "error" => $error_message );
                }

                $position->end_month = $this->request->post( "end-month" );
                $position->end_year = $this->request->post( "end-year" );
            }

            $positionRepo = $this->load( "position-repository" );
            $position = $positionRepo->persist( $position );

            // Return the position entity if request is asynchronous
            if ( $this->request->isAjax() ) {
                $view->respond()
                ->setSuccess( true )
                ->setHttpStatusCode( 201 )
                ->setData( [ $position ] )
                ->send();
            }

            // If is form submission, redirect back to where the form was submitted
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

    public function deleteAction( $id = null )
    {
        if ( is_null( $id ) ) {
            $view = $this->view( "Resume/Position/Delete" );
            $view->renderTemplate( "Errors/404.php" );
        }

        $view = $this->view( "Resume/Position/Delete" );
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
            $positionRepo = $this->load( "position-repository" ); 

            $position = $positionRepo->select( "id" )
                ->whereColumnValue( "id", "=", $id )
                ->and()->columnValue( "user_id", "=", $this->user->id )
                ->execute();


            if ( is_null( $position ) ) {
                if ( $this->request->isAjax() ) {
                    $view->respond()
                        ->setSuccess( false )
                        ->setHttpStatusCode( 404 )
                        ->addMessage( "Resource not found" )
                        ->send();
                }

                $view->backWithData( [ "error" => "Resource not found" ] );
            }

            $positionRepo->deleteEntity( $position );

            if ( $this->request->isAjax() ) {
                $view->respond()
                    ->setSuccess( true )
                    ->setHttpStatusCode( 204 )
                    ->addMessage( "Resource deleted successfully" )
                    ->send();
            }

            $view->back();
        }

        // Return the position entity if request is asynchronous
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