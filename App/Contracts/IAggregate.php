<?php

namespace Contracts;

use Contracts\EntityInterface;

interface IAggregate
{
	public function __construct( EntityInterface $entity );
}