<?php

namespace Model\Services;

use Model\Mappers\DataMapper;
use Contracts\RepositoryInterface;

abstract class Repository implements RepositoryInterface
{
    protected $entityName;
    protected $mapper;
    protected $mapperNamespace;

    // New Repository
    protected $comparison_operators = [ "=", "<", "<=", ">", ">=", "<>" ];
    protected $logical_operators = [ "is", "not", "is not", "in", "not in", "is null" ];
    protected $arithmetic_operators = [ "+", "-", "/", "*", "%" ];
    protected $return_formats = [ "entity-array", "entity", "collection", "json", "array"  ];
    protected $query_pointer = 0;
    protected $parameterization_pointer = 0;
    public $token_map = []; // TODO change to protected
    protected $executed_queries = [];
    protected $query_count = 0;
    public $queries = []; // TODO change to protected
    protected $query = null;
    protected $expression_starts = 0;
    protected $expression_ends = 0;

    public function __construct( $dao, $entityFactory )
    {
        $this->buildEntityName();
        $this->setMapperNamespace( "\Model\Mappers\\" );

        $this->setMapper( $this->buildMapper( $dao, $entityFactory ) );
    }

    // The magic __call method will be used to catch calls to methods 'and' and 'or'
    // only. The purpose of this circumvent the reserved word rule which restricts
    // both 'and' and 'or' from being used as method names. This will allow queries
    // to be built sytactically without using ugly and hard to remember method names
    // such as ::_and() and ::_or(). Those methods will insted be called in the __call
    // method below.
    public function __call( $name, $args )
    {
        switch ( $name ) {
            case "and":
                return $this->_and();
                break;
            case "or":
                return $this->_or();
                break;
        }
        
        throw new \BadMethodCallException();
    }

    protected function setMapper( DataMapper $mapper )
    {
        $this->mapper = $mapper;
    }

    protected function getMapper()
    {
        if ( isset( $this->mapper ) == false ) {
            throw new \Exception( "'Mapper' is not set" );
        }

        return $this->mapper;
    }

    protected function buildMapper( $dao, $entityFactory )
    {
        $mapperName = $this->buildMapperName();

        $mapper = new $mapperName( $dao, $entityFactory );

        return $mapper;
    }

    protected function buildEntityName()
    {
        // Derive the name of the mapper and entity from the class name of this repository
        $repositoryClassName = explode( "\\", get_class( $this ) );
        $entityName = $this->mapperNamespace . str_replace( "Repository", "", end( $repositoryClassName ) );
        
        // Set entity name
        $this->setEntityName( $entityName );
    }

    protected function buildMapperName()
    {
        return $this->mapperNamespace . $this->entityName . "Mapper";
    }

    protected function setEntityName( $entityName )
    {
        $this->entityName = $entityName;
    }

    protected function setMapperNamespace( $namespace )
    {
        $this->mapperNamespace = $namespace;
    }

    // Basic CRUD
    public function insert( array $key_values, $return_object = true )
    {
        $mapper = $this->getMapper();
        $entity = $mapper->build( $this->entityName );

        return $mapper->insert( $key_values, $return_object );
    }

    public function save( $entity )
    {
        // Make sure the correct entity type was provided
        $this->validateEntity( $entity );

        $mapper = $this->getMapper();

        return $mapper->save( $entity );
    }

    public function persist( $entity )
    {
        return $this->save( $entity );
    }

    public function duplicateAndPersist( $entity )
    {
        // Save the entity but remove the id property to ensure the new row
        // created in the db will have a unique id
        if ( isset( $entity->id ) ) {
            unset( $entity->id );
        }

        return $this->persist( $entity );
    }

    public function get( array $columns, $key_values = [], $return_format = "array" )
    {
        if ( !is_array( $key_values ) ) {
            throw new \Exception( "key_values argument must be an array" );
        }

        if ( func_num_args() > 2 ) {
            $return_format = func_get_args()[ 2 ];
        }

        $mapper = $this->getMapper();
        $result = $mapper->get( $columns, $key_values, $return_format );

        return $result;
    }

    public function update( \Contracts\EntityInterface $entity )
    {
        // Check if entity provided has an 'id' property. If not, throw exception
        if ( isset( $entity->id ) ) {

            // Get the original entity from storage
            $originalEntity = $this->select( "*" )
                ->whereColumnValue( "id", "=", $entity->id )
                ->execute( "entity" );

            // If the original entity return from storage is null, throw exception.
            if ( !is_null( $originalEntity ) ) {

                // Determine which properties have been changed from the original
                // entity and save them to an array
                $props = get_object_vars( $originalEntity );
                $propsToUpdate = [];

                foreach ( $props as $prop => $originalValue ) {
                    if ( $entity->$prop != $originalValue ) {
                        $propsToUpdate[ $prop ] = $entity->$prop;
                    }
                }

                if ( !empty( $propsToUpdate ) ) {
                    $this->updateColumns( $propsToUpdate, [ "id" => $entity->id ] );
                }

                return $entity;
            }

            // Throw exception if the object return from storage was null
            throw new \Exception( "Entity does not exist to be updated." );
        }

        throw new \Exception( "Entity must have an 'id' in order be updated." );
    }

    public function updateColumns( array $columns_to_update, array $where_columns )
    {
        $mapper = $this->getMapper();
        $mapper->update( $columns_to_update, $where_columns );
    }

    public function deleteByKeyValue( array $keys, array $values )
    {
        $mapper = $this->getMapper();
        $mapper->delete( $keys, $values );
    }

    public function delete( $entities )
    {
        $mapper = $this->getMapper();

        // If entities is an array, iterate through the array and delete all
        // entities from the database
        if ( is_array( $entities ) ) {
            foreach ( $entities as $entity ) {
                $this->validateEntity( $entity );
                // Delete this entity
                $mapper->delete(
                    [ "id" ],
                    [ $entity->id ]
                );
            }

            return;
        }

        // If entites arg is a single entity, then delete this entity
        $this->validateEntity( $entities );
        $mapper->delete( [ "id" ], [ $entities->id ] );
        
        return;
    }

    private function validateEntity( $entity )
    {
        if ( !is_a( $entity, "Model\\Entities\\{$this->entityName}" ) ) {
            throw new \Exception( "Entity invalid. Must be of class 'Model\\Entities\\{$this->entityName}' - '" . get_class( $entity ) . "' provided" );
        }

        return;
    }

    /*
    * New API
    */
    public function select( $cols )
    {
        // This runs before every query
        $this->startQuery();

        $this->query = "SELECT";

        if ( is_array( $cols ) ) {
            foreach ( $cols as $key => $col ) {
                $cols[ $key ] = "`{$col}`";
            }
            $this->query .= " " . implode( ", ", $cols ) . " FROM `{$this->mapper->getTable()}`";

            return $this;
        }

        if ( $cols == "*" ) {
            $this->query .= " {$cols} FROM `{$this->mapper->getTable()}`";

            return $this;
        }

        $this->query .= " `{$cols}` FROM `{$this->mapper->getTable()}`";

        return $this;
    }

    public function first( $cols, $return_format = "entity" )
    {
        $response = $this->select( $cols )
            ->orderBy( "id", "desc" )->limit( 1 )->execute( $return_format );

        if (
                $return_format == "array" &&
                is_array( $response ) &&
                !empty( $response )
        ) {
            return $response[ 0 ];
        }

        return $response;
    }

    public function last( $cols, $return_format = "entity" )
    {
        $response = $this->select( $cols )
            ->orderBy( "id", "asc" )->limit( 1 )->execute( $return_format );

        if (
                $return_format == "array" &&
                is_array( $response ) &&
                !empty( $response )
        ) {
            return $response[ 0 ];
        }

        return $response;
    }

    public function where()
    {
        $this->query .= " WHERE";
        return $this;
    }

    public function _and()
    {
        $this->query .= " AND";
        return $this;
    }

    public function is()
    {
        $this->query .= " IS";
        return $this;
    }

    public function not()
    {
        $this->query .= " NOT";
        return $this;
    }

    public function andStart()
    {
        $this->query .= " AND";
        return $this->expressionStart();
    }

    public function andEnd()
    {
        return $this->expressionEnd();
    }

    public function _or()
    {
        $this->query .= " OR";
        return $this;
    }

    public function orStart()
    {
        $this->query .= " OR";
        return $this->expressionStart();
    }

    public function orEnd()
    {
        return $this->expressionEnd();
    }

    public function column( $col )
    {
        $this->query .= " `{$col}`";

        return $this;
    }

    public function whereColumn( $col )
    {
        return $this->where()->column( $col );
    }

    public function value( $operator, $value = null )
    {
        $this->validateOperator( $operator );

        // Null values are only allowed if 'is' or 'is not' operator is used
        if ( is_null( $value ) && !in_array( $operator, $this->logical_operators ) ) {
            throw new \Exception( "Value in 'where' clause cannot be null unless 'is' or 'is not' operator is used" );
        }

        // Require an array for 'in' and 'not in'. Make lowercase because it was transformed already
        if ( in_array( strtolower( $operator ), [ "in", "not in" ] ) ) {
            
            $this->validateInValue( $value );

            switch ( strtolower( $operator ) ) {
                case "in":
                    $this->in( $value );
                    break;
                case "not in":
                    $this->notIn( $value );
                    break;
            }

            return $this;
        }

        // If value is null, transform to all capital NULL
        $value = (
            is_null( $value ) ?
            "NULL" :
            $this->parameterizeValue( $value )
        );

        // Tranform case of logical operator
        if ( in_array( $operator, $this->logical_operators ) ) {
            $operator = strtoupper( $operator );
        }

        $this->query .= " {$operator} {$value}";

        return $this;
    }

    public function whereColumnValue( $col, $operator, $value = null )
    {
        return $this->whereColumn( $col )->value( $operator, $value );
    }

    public function columnValue( $col, $operator, $value = null )
    {
        return $this->column( $col )->value( $operator, $value );
    }

    public function in( array $array )
    {
        if ( empty( $array ) ) {
            throw new \Exception( "The array provided for argument 'array' cannot be empty" );
        }

        // Start the query with the operator
        $this->query .= " IN (";

        // Get the number of items in the array
        $item_count = count( $array );

        $iteration = 1;
        foreach ( $array as $item ) {
            if ( !is_scalar( $item ) ) {
                throw new \Exception( "Function '::buildInQuery' expects all items in provided array to be scalar." );
            }

            // Tranform null items to uppercase string 'NULL' or put the item in single quotes
            $item = (
                is_null( $item ) ?
                "NULL" :
                $this->parameterizeValue( $item )
            );

            // Check if last item in array. If so, don't put a comma and close the parenthesis
            $ending = ( $iteration != $item_count ? ", " : " )" );

            $this->query .= " {$item}{$ending}";

            $iteration++;
        }

        return $this;
    }

    public function notIn( array $array )
    {
        // If the array is empty, do not add anything to the query
        if ( empty( $array ) ) {
            return $this;
        }
        
        return $this->not()->in( $array );
    }

    public function orderBy( $col, $order )
    {
        if ( !in_array( strtolower( $order ), [ "asc", "desc" ] ) ) {
            throw new \Exception( "Invalid 'order' argument supplied" );
        }

        $order = strtoupper( $order );

        $this->query .= " ORDER BY `{$col}` {$order}";

        return $this;
    }

    public function limit( $number )
    {
        if ( !is_integer( $number ) ) {
            throw new \Exception( "Argument in 'limit' must be an integer" );
        }

        $this->query .= " LIMIT {$number}";

        return $this;
    }

    public function expressionStart()
    {
        $this->expression_starts++;

        $this->query .= " (";

        return $this;
    }

    public function expressionEnd()
    {
        $this->expression_ends++;

        $this->query .= " )";

        return $this;
    }

    // Value parameterization
    protected function parameterizeValue( $value )
    {
        $parameterization_token = ":token" . $this->parameterization_pointer;
        $this->token_map[ $this->query_pointer ][ $parameterization_token ] = $value;

        // Iterate the parameterization_pointer
        $this->parameterization_pointer++;

        return $parameterization_token;
    }

    // Query executions

    // Stores the query in an array to be executed later
    public function queue( $return_format = "entity-array" )
    {
        $this->validateReturnFormat( $return_format );

        $this->validateExpressions();

        // End query with semicolon
        $this->query .= ";";

        // Add to query queue
        $this->queries[ $this->query_pointer ] = [
            "query" => $this->query,
            "return_format" => $return_format
        ];

        // Reset the query to null
        $this->resetQuery();

        return $this;
    }

    // Executes the query with the data mapper
    public function execute( $return_format = "entity-array" )
    {
        $this->validateReturnFormat( $return_format );

        $this->validateExpressions();

        // End query with semicolon
        $this->query .= ";";

        // Update executed queries list
        $this->executed_queries[] = $this->query;

        $query_to_run = $this->query;

        // Reset query and parameterization pointer
        $this->resetQuery();
        $this->resetParameterizationPointer();

        // TODO Consider checking if index $this->query_pointer isset in $this->token_map
        return $this->mapper->execute(
            $query_to_run,
            $this->token_map[ $this->query_pointer ],
            $return_format
        );
    }

    public function commit()
    {
        $this->resetParameterizationPointer();

        $responses = [];

        // IMPORTANT: The key of each queued query and they key of each queued query's
        // parameter token map are the same
        foreach ( $this->queries as $query_pointer => $query ) {
            $token_map = [];
            if ( isset( $this->token_map[ $query_pointer ] ) ) {
                $token_map = $this->token_map[ $query_pointer ];
            } 

            $responses[] = $this->mapper->execute(
                $query[ "query" ],
                $token_map,
                $query[ "return_format" ]
            );
        }

        return $responses;
    }

    // Resets

    private function startQuery()
    {
        // Reset the current query
        $this->resetQuery();

        // Increment the query pointer
        $this->query_pointer++;

        // Initialize an empty token map
        $this->token_map[ $this->query_pointer ] = [];
    }

    protected function resetQuery()
    {
        $this->expression_starts = 0;
        $this->expression_ends = 0;
        $this->query = null;
    }

    protected function resetParameterizationPointer()
    {
        $this->parameterization_pointer = 0;
    }

    // Validations

    protected function validateReturnFormat( $return_format )
    {
        if ( !in_array( $return_format, $this->return_formats ) ) {
            throw new \Exception( "Invalid return format used" );
        }
    }

    protected function validateOperator( $operator )
    {
        if (
            !in_array( $operator, $this->comparison_operators ) &&
            !in_array( $operator, $this->logical_operators )
        ) {
            throw new \Exception( "Invalid operator. `{$operator}` provided" );
        }
    }

    protected function validateComparisonOperator( $operator )
    {
        if ( !in_array( $operator, $this->comparison_operators ) ) {
            throw new \Exception( "Invalid comparison operator provided" );
        }
    }

    protected function validateLogicalOperator( $operator )
    {
        if ( !in_array( $operator, $this->logical_operators ) ) {
            throw new \Exception( "Invalid logical operator provided" );
        }
    }

    protected function validateInValue( $value )
    {
        if ( !is_array( $value ) ) {
            throw new \Exception( "Value must be of type 'array' when using the 'in' and 'not in' operators" );
        }
    }

    protected function validateExpressions()
    {
        // Validate # of expression 'starts' and 'ends'. Throw error If !=
        if ( $this->expression_starts != $this->expression_ends ) {
            throw new \Exception( "Number of expression 'starts' and 'ends' does not match. starts = {$this->expression_starts}; ends = {$this->expression_ends}" );
        }
    }
}
