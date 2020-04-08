<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class Accomplishment implements EntityInterface
{
	public $id;
	public $user_id;
	public $description;
}