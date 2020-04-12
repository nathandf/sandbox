<?php

namespace Controllers\Resume;

use Core\BaseController;

class Experience extends BaseController
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
        $view = $this->view( "Resume/Experience/Index" );
        
        $experienceRepo = $this->load( "experience-repository" );
        $experiences = $experienceRepo->select( "*" )
            ->whereColumnValue( "user_id", "=", $this->user->id )
            ->execute();

        $experienceList = [];

        // Get the employer and duty entities related to this experience
        $employerRepo = $this->load( "employer-repository" );
        $dutyRepo = $this->load( "duty-repository" );
        
        foreach ( $experiences as $experience ) {

            // Employer
            $experience->employer = $employerRepo->select( "*" )
                ->whereColumnValue( "id", "=", $experience->employer_id )
                ->execute( "entity" );

            // Duties
            $experience->dutyList = $dutyRepo->select( "*" )
                ->whereColumnValue( "experience_id", "=", $experience->id )
                ->execute();

            $experienceList[] = $experience;
        }

        $view->assign( "experienceList", $experienceList );

        $view->render();
    }
}