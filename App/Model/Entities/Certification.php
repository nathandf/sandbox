<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class Certification implements EntityInterface
{
	public $id;
	public $user_id;
	public $name;
	public $description;
	public $issued_by;
	public $date_awarded;
}