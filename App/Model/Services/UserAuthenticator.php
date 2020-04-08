<?php

namespace Model\Services;

use Core\Http\Request,
    Model\Entities\User;

class UserAuthenticator
{
    public $userRepo;
    public $request;
    public $authenticatedUser = null;
    public $user_authenticated;

    public function __construct( UserRepository $userRepo, Request $request )
    {
        $this->userRepo = $userRepo;
        $this->request = $request;

        // Check for a logged in user
        $this->user_authenticated = $this->isLoggedIn();
    }

    public function authenticate( $email, $password )
    {
        $user = $this->userRepo->get( [ "*" ], [ "email" => strtolower( trim( $email ) ) ], "single" );

        if ( !is_null( $user ) ) {
            if ( password_verify( $password, $user->password ) ) {
                $this->setAuthenticatedUser( $user );
                $this->logIn( $user );

                return true;
            }

            return false;
        }

        return false;
    }

    public function logOut()
    {
        if ( session_status() != PHP_SESSION_NONE ) {
            session_unset();
            session_destroy();
        }

        $this->request->deleteCookie( "user-token" );
    }

    public function logIn( User $user )
    {
        $token = $this->request->generateToken();
        $this->request->setSession( "user-id", $user->id );
        $this->request->setCookie( "user-token", $token );

        $user->token = $token;
        
        $this->userRepo->update( $user );
    }

    public function isLoggedIn()
    {
        if ( $this->request->session( "user-id" ) ) {

            // Get the user from storage based on the user id set in the session
            $user = $this->userRepo->select( "*" )
                ->whereColumnValue( "id", "=", $this->request->session( "user-id" ) )
                ->execute( "entity" );

            // If no user is returned, either the session doesn't have a user-id or
            // or the user-id set in the session no longer exists
            if ( is_null( $user ) ) {
                return false;
            }

            // Store the return user entity as the authenticatedUser
            $this->setAuthenticatedUser( $user );

            return true;

        } elseif ( $this->request->cookie( "user-token" ) ) {
            $user = $this->userRepo->get( [ "*" ], [ "token" => $this->request->cookie( "user-token" ) ], "single" );
            if ( is_null( $user ) ) {
                return false;
            }
            $this->setAuthenticatedUser( $user );

            return true;
        }

        return false;
    }

    private function setAuthenticatedUser( User $user )
    {
        $this->authenticatedUser = $user;
        return $this;
    }

    public function getAuthenticatedUser()
    {
        return $this->authenticatedUser;
    }
}
