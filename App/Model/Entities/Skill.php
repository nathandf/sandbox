<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class Skill implements EntityInterface
{
	public $id;
	public $user_id;
	public $description;
}