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

    public function create()
    {
        $view = $this->view( "Resume/Education/Delete" );
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
            $employerRepo = $this->load( "employer-repository" );
            $entityFactory = $this->load( "entity-factory" );

            $employer = $entityFactory->build( "Employer" );
            $employer->name = $this->request->post( "name" );
            $employer->city = $this->request->post( "city" );
            $employer->state = $this->request->post( "state" );
            $employer->currently_attending = $this->request->post( "current-attending" );
            $employer->month_graduated = $this->request->post( "month_graduated" );
            $employer->currently_attending = $this->request->post( "year_graduated" );

            $employerRepo->save();

            // Return the employer entity if request is asynchronous
            if ( $this->request->isAsync() ) {
                $view->respondWithJson();
            }

            // If is form submission, redirect back to where the form was submitted
            $view->back();
        }

        $view->backWithData( [ "error" => $requestValidator->getError( 0 ) ], true );
    }
}