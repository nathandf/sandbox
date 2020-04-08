<?php

namespace Views\Home;

use Core\View;

class SignIn extends View
{
	public function render()
	{
		$formData = $this->getData( "formData" );

		if ( !is_null( $formData ) ) {
			$this->assign( "email", $formData->email );
		}

		return $this->renderTemplate( "Home/SignIn.php" );
	}
}
