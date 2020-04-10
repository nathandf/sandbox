<?php

namespace Views\User;

use Core\View;

class Index extends View
{
	public function render()
	{
		return $this->renderTemplate( "Home/Index.php" );
	}
}
