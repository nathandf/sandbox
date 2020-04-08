<?php

namespace Model\Entities;

use Contracts\EntityInterface;
use Model\DomainObjects\Person;

class User extends Person implements EntityInterface
{
	public $id;
	public $status; // unavailable, available, pending_commitment, fulfilling_request
	public $role;
	public $first_name;
	public $last_name;
	public $email;
	public $phone_id;
	public $address_id;
	public $password;
	public $token;
	public $image_id;
	public $current_account_id;
	public $is_professional;
	public $is_volunteer;

	public function setRole( $role )
	{
		$this->role = $role;
		
		return $this;
	}

	public function setPassword( $password )
	{
		$this->password = password_hash( trim( $password ), PASSWORD_BCRYPT );
		return $this;
	}

	public function getPassword()
	{
		return $this->password;
	}
}