<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class Duty implements EntityInterface
{
	public $id;
	public $user_id;
	public $employment_id;
	public $description;
}