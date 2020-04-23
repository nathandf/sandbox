<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class Employer implements EntityInterface
{
	public $id;
	public $user_id;
	public $name;
	public $city;
	public $region;
	public $country;
}