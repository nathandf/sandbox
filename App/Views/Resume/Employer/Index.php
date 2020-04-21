<?php

namespace Views\Resume\Employer;

use Core\WebPage;

class Index extends WebPage
{
	public function render()
	{
		return $this->renderTemplate( "Resume/Employer/Index.php" );
	}
}