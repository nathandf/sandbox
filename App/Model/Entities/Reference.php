<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class Reference implements EntityInterface
{
	public $id;
	public $user_id;
	public $name;
	public $phone_number;
	public $email;
	public $relationship;
}