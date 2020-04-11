<?php

namespace Views\Resume\Education;

use Core\WebPage;

class Index extends WebPage
{
	public function render()
	{
		return $this->renderTemplate( "Resume/Education/Index.php" );
	}
}