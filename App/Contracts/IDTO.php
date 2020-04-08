<?php

namespace Contracts;

interface IDTO
{
	public function arrayToDTO( array $array );
	public function objectToDTO( $object );
}