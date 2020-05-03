<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class Position implements EntityInterface
{
	public $id;
	public $user_id;
	public $employer_id;
	public $name;
	public $start_month;
	public $start_year;
	public $end_month;
	public $end_year;
	public $currently_employed;
}