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

    public function create()
    {
        $requestValidator = $this->load( "request-validator" );

        if (
            $this->request->is( "post" ) &&
            $requestValidator->validate(
                $this->request,
                [
                    "csrf-token" => [
                        "required" => true
                    ],
                    "employer_id" => [
                    ],
                    "name" => [
                        "max" => 256
                    ],
                    "city" => [
                        "max" => 256
                    ],
                    "state" => [
                        "max" => 256
                    ],
                    "country" => [],
                    "position" => [],
                    "currently-employed" => [],
                    "start-month" => [],
                    "start-year" => [],
                    "end-month" => [],
                    "end-year" => []
                ],
                "new-employment"
            )
        ) {
            ppd( $this->request->post() );
        }
    }

    public function deleteAction( $id = null )
    {
        $view = $this->view( "Resume/Employer/Delete" );

        if ( is_null( $id ) ) {
            $view->redirect( HOME . "resume/employers/" );
        }

        $employerRepo = $this->load( "employer-repository" );

        $employerRepo->delete()
            ->whereColumnValue( "user_id", "=", $this->user->id )
            ->and()->columnValue( "id", "=", $id )
            ->execute( null );
    }
}