<?php

namespace Views\Resume\Certifications;

use Core\WebPage;

class Index extends WebPage
{
	public function render()
	{
		return $this->renderTemplate( "Resume/Certifications/Index.php" );
	}
}