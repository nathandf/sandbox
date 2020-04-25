<?php

namespace Views\Resume\Accomplishment;

use Core\WebPage;

class Index extends WebPage
{
	public function render()
	{
		return $this->renderTemplate( "Resume/Accomplishment/Index.php" );
	}
}