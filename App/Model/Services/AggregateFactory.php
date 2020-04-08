<?php

namespace Model\Services;

use Contracts\EntityInterface;

class AggregateFactory
{
    private $namespace = "\\Model\\Entities\\Aggregates\\";

    public function build( $type, EntityInterface $root_entity )
    {
        if ( $type == "" ) {
            throw new \Exception( "Invalid Entity Type: \"$type\"." );
        }

        $class = $this->namespace . ucwords( $type );
        if ( class_exists( $class ) ) {
            return new $class( $root_entity );
        }

        throw new \Exception( "Class {$type} does not exist" );
    }

    public function setNamespace( $namespace )
    {
        $this->namespace = $namespace;

        return $this;
    }
}
