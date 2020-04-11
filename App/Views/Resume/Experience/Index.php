<?php

namespace Views\Resume\Experience;

use Core\WebPage;

class Index extends WebPage
{
	public function render()
	{
		return $this->renderTemplate( "Resume/Experience/Index.php" );
	}
}