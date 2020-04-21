<?php

namespace Views\Resume\Employers;

use Core\WebPage;

class Index extends WebPage
{
	public function render()
	{
		return $this->renderTemplate( "Resume/Employers/Index.php" );
	}
}