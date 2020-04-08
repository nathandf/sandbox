<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class Account implements EntityInterface
{
	public $id;
	public $account_type_id;
	public $user_id;
}