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
        $view = $this->view( "Resume/Employer/Delete" );

        if ( is_null( $id ) ) {
            $view->redirect( HOME . "resume/employers/" );
        }

        $employerRepo = $this->load( "employer-repository" );

        $employerRepo->delete()
            ->whereColumnValue( "user_id", "=", $this->user->id )
            ->and()->columnValue( "id", "=", $id )
            ->execute();
    }
}