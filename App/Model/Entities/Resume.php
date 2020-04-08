<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class Resume implements EntityInterface
{
	public $id;
	public $user_id;
	public $name;
	public $description;
	public $phone_number;
	public $email;
	public $career_objective_id;
	public $position;
}