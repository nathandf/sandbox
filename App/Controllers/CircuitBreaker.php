<?php

namespace Controllers;

use Core\BaseController;

class CircuitBreaker extends BaseController
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
        $nasdaqShorthaltParser = $this->load( "nasdaq-shorthalt-parser" );

        $start_year = 2011;
        $end_year = 2011;

        $years = range( $start_year, $end_year );
        $months = range( 1, 12 );
        $days = range( 1, 31 );

        foreach ( $years as $year ) {
            $year = (string)$year;

            foreach ( $months as $month ) {
                if ( $month < 10 ) {
                    $month = "0" . (string)$month;
                }

                $month = (string)$month;

                foreach ( $days as $day ) {
                    if ( $day < 10 ) {
                        $day = "0" . (string)$day;
                    }

                    $day = (string)$day;

                    $date = $year . $month . $day;

                    if ( $nasdaqShorthaltParser->parse( $date ) ) {
                        file_put_contents(
                            "../datasets/nasdaq/shorthalts/" . (string)$date . ".json",
                            json_encode( $nasdaqShorthaltParser->getData(), JSON_PRETTY_PRINT )
                        );
                    }
                }
            }
        }
	}
}