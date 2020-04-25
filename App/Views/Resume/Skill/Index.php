<?php

namespace Views\Resume\Skill;

use Core\WebPage;

class Index extends WebPage
{
	public function render()
	{
		return $this->renderTemplate( "Resume/Skill/Index.php" );
	}
}