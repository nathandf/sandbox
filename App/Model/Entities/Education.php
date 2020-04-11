<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class Education implements EntityInterface
{
	public $id;
	public $user_id;
	public $institution;
	public $city;
	public $state;
	public $year_graduated;
	public $month_graduated;
	public $award;
	public $currently_attending;
}