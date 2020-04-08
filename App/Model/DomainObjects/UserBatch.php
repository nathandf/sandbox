<?php

namespace Model\DomainObjects;

use Contracts\DomainObjectInterface;
use Model\Entities\User;

class UserBatch implements DomainObjectInterface
{
	public $users = [];

	public function register( array $users )
	{
		if ( empty( $users ) ) {
			return;
		}

		foreach ( $users as $user ) {
			if ( $this->validate( $user ) ) {
				$this->users[] = $user;
			}
		}

		return;
	}

	private function validate( $user )
	{
		if ( $user instanceof User ) {
			return true;
		}

		return false;
	}
}