<?php

namespace Views\Resume\Skills;

use Core\WebPage;

class Index extends WebPage
{
	public function render()
	{
		return $this->renderTemplate( "Resume/Skills/Index.php" );
	}
}