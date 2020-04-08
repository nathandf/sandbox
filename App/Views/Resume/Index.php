<?php

namespace Views\Resume;

use Core\View;

class Index extends View
{
	public function render()
	{
		return $this->renderTemplate( "Resume/Index.php" );
	}
}