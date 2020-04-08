<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class Certification implements EntityInterface
{
	public $id;
	public $user_id;
	public $title;
	public $description;
	public $issed_by;
	public $date_awarded;
}