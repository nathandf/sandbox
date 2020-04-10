<?php

namespace Views\Resume;

use Core\WebPage;

class Index extends WebPage
{
	public function render()
	{
		return $this->renderTemplate( "Resume/Index.php" );
	}
}