<?php

namespace Controllers\Resume;

use Core\BaseController;

class Education extends BaseController
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
        $view = $this->view( "Resume/Education/Index" );
        
        $educationRepo = $this->load( "education-repository" );
        $educationList = $educationRepo->select( "*" )
            ->whereColumnValue( "user_id", "=", $this->user->id )
            ->execute();

        $view->assign( "educationList", $educationList );

        $view->render();
    }

    public function createAction()
    {
        $view = $this->view( "Resume/Education/Create" );
        $requestValidator = $this->load( "request-validator" );

        if (
            $this->request->is( "post" ) &&
            $requestValidator->validate(
                $this->request,
                [
                    "csrf-token" => [
                        "required" => true
                    ],
                    "institution" => [
                        "required" => true,
                        "max" => 256
                    ],
                    "city" => [
                        "required" => true,
                        "max" => 256
                    ],
                    "state" => [
                        "required" => true,
                        "max" => 256
                    ],
                    "currently-attending" => [],
                    "month-graduated" => [],
                    "year-graduated" => []
                ]
            )
        ) {
            $educationRepo = $this->load( "education-repository" );
            $entityFactory = $this->load( "entity-factory" );

            $education = $entityFactory->build( "Education" );
            $education->user_id = $this->user->id;
            $education->institution = $this->request->post( "institution" );
            $education->city = $this->request->post( "city" );
            $education->state = $this->request->post( "state" );
            $education->currently_attending = $this->request->post( "currently-attending" );
            
            if ( !$education->currently_attending ) {
                $education->month_graduated = $this->request->post( "month-graduated" );
                $education->year_graduated = $this->request->post( "year-graduated" );
                $education->award = $this->request->post( "award" );
            }

            $education = $educationRepo->persist( $education );

            // Return the education entity if request is asynchronous
            if ( $this->request->isAjax() ) {
                $view->respond()
                ->setSuccess( true )
                ->setHttpStatusCode( 201 )
                ->setData( [ $education ] )
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
            $view = $this->view( "Resume/Education/Delete" );
            $view->renderTemplate( "Errors/404.php" );
        }

        $view = $this->view( "Resume/Education/Delete" );
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
            $educationRepo = $this->load( "education-repository" ); 

            $education = $educationRepo->select( "id" )
                ->whereColumnValue( "id", "=", $id )
                ->and()->columnValue( "user_id", "=", $this->user->id )
                ->execute();


            if ( is_null( $education ) ) {
                if ( $this->request->isAjax() ) {
                    $view->respond()
                        ->setSuccess( false )
                        ->setHttpStatusCode( 404 )
                        ->addMessage( "Resource not found" )
                        ->send();
                }

                $view->backWithData( [ "error" => "Resource not found" ] );
            }

            $educationRepo->deleteEntity( $education );

            if ( $this->request->isAjax() ) {
                $view->respond()
                    ->setSuccess( true )
                    ->setHttpStatusCode( 204 )
                    ->addMessage( "Resource deleted successfully" )
                    ->send();
            }

            $view->back();
        }

        // Return the education entity if request is asynchronous
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