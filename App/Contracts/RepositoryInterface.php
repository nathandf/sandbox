<?php

namespace Contracts;

interface RepositoryInterface
{
	public function insert( array $key_values );
	public function get( array $columns );
	public function update( EntityInterface $entity );
}
