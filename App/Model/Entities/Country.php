<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class Country implements EntityInterface
{
	public $id;
	public $iso;
	public $iso3;
	public $nicename;
	public $numcode;
	public $phonecode;
}