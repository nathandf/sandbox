<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class Position implements EntityInterface
{
	public $id;
	public $user_id;
	public $employer_id;
	public $name;
	public $month_start;
	public $year_start;
	public $month_end;
	public $year_end;
	public $currently_employed;
}