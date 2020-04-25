<?php

namespace Views\Resume\Certification;

use Core\WebPage;

class Index extends WebPage
{
	public function render()
	{
		return $this->renderTemplate( "Resume/Certification/Index.php" );
	}
}