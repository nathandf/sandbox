<?php

namespace Model\Services;

class DtoFactory
{
    private $namespace = "Model\\DTOs\\";

	public function build( $class_name, array $args = [] )
	{	
		$full_class_name = $this->namespace . $class_name;
		$dto = new $full_class_name( ...$args );

		return $dto;
	}
}