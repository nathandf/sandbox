<?php

namespace Views\Resume\Accomplishments;

use Core\WebPage;

class Index extends WebPage
{
	public function render()
	{
		return $this->renderTemplate( "Resume/Accomplishments/Index.php" );
	}
}