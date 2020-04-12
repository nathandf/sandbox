<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class Duty implements EntityInterface
{
	public $id;
	public $user_id;
	public $experience_id;
	public $description;
}