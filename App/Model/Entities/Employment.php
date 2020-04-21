<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class Employment implements EntityInterface
{
	public $id;
	public $user_id;
	public $employer_id;
	public $position;
	public $month_start;
	public $year_start;
	public $month_end;
	public $year_end;
	public $currently_employed;
}