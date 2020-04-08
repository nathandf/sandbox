<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class Experience implements EntityInterface
{
	public $id;
	public $user_id;
	public $employer_id;
	public $position;
	public $start;
	public $end;
}