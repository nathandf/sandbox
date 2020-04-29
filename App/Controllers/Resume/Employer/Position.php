<?php

namespace Controllers\Resume\Employer;

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

        $employerRepo = $this->load( "employer-repository" );

        $this->employer = $employerRepo->select( "*" )
            ->whereColumnValue( "id", "=", $this->request->route( "parentid" ) )
            ->and()->columnValue( "user_id", "=", $this->user->id )
            ->execute( "entity" );

        if ( is_null( $this->employer ) ) {
            $view = $this->view( "Errors/Error" );
            if ( $this->request->isAjax() ) {
                $view->response()
                    ->respondWithError( 404 );
            }

            $view->renderHttpErrorCode( 404 );
        }
    }
    
    public function indexAction()
    {
        ppd( "test" );
    }

    public function createAction()
    {
        ppd( $this->request );
    }
}