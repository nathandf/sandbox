<?php

namespace Controllers\Resume;

use Core\BaseController;

class Employment extends BaseController
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
        $view = $this->view( "Resume/Employment/Index" );
        
        $employmentRepo = $this->load( "employment-repository" );
        $employments = $employmentRepo->select( "*" )
            ->whereColumnValue( "user_id", "=", $this->user->id )
            ->execute();

        $employmentList = [];

        // Get all employers and duty entities related to each employment
        $employerRepo = $this->load( "employer-repository" );
        $employerList = $employerRepo->select( "*" )
            ->whereColumnValue( "user_id", "=", $this->user->id )
            ->execute();

        $view->assign( "employerList", $employerList );

        $dutyRepo = $this->load( "duty-repository" );
        
        foreach ( $employments as $employment ) {

            // Employer
            $employment->employer = $employerRepo->select( "*" )
                ->whereColumnValue( "id", "=", $employment->employer_id )
                ->execute( "entity" );

            // Duties
            $employment->dutyList = $dutyRepo->select( "*" )
                ->whereColumnValue( "employment_id", "=", $employment->id )
                ->execute();

            $employmentList[] = $employment;
        }

        $view->assign( "employmentList", $employmentList );

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
}