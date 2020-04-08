<?php

namespace Model\Services;

class RepositoryFactory
{
	public $entityFactory;
	public $dao;
	private $namespace = "Model\\Services\\";

	public function __construct( $dao, EntityFactory $entityFactory  )
	{
		$this->dao = $dao;
		$this->entityFactory = $entityFactory;
	}

	public function build( $class_name )
	{	
		$full_class_name = $this->namespace . $class_name;
		$repo = new $full_class_name( $this->dao, $this->entityFactory );

		return $repo;
	}

	public function setRepositoryNamespace( $namespace )
	{
		$this->namespace = $namespace;
		return $this;
	}
}