<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class Address implements EntityInterface
{
	public $id;
	public $address_1;
	public $address_2;
	public $city;
	public $region;
	public $postal;
	public $country;
	public $country_id;
}