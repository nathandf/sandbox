<?php

namespace Views\Home;

use Core\WebPage;

class Index extends WebPage
{
	public function render()
	{
		return $this->renderTemplate( "Home/Index.php" );
	}
}
