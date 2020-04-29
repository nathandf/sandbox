<?php

namespace Views\Resume\Position;

use Core\WebPage;

class Index extends WebPage
{
	public function render()
	{
		return $this->renderTemplate( "Resume/Position/Index.php" );
	}
}