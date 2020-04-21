<?php

namespace Model\Mappers;

use Contracts\DataMapperInterface;

abstract class DataMapper implements DataMapperInterface
{
    protected $validReturnTypes = [
        "entity_array", "entity", "collection", "single", "array", "json", "raw"
    ];
    protected $database;
    protected $table;
    public $entityFactory;

    public function __construct( $dao, \Model\Services\EntityFactory $entityFactory )
    {
        $this->database = $dao;
        $this->entityFactory = $entityFactory;
        $this->setTable();
    }

    public function save( $entity )
    {
        // Get the columns of this table
        $columns = $this->getColumns();

        // Get the properties of the provided entity
        $enitity_properties = get_object_vars( $entity );

        // Build an array of key-value pairs of entity properties only if they exists
        // in the database
        $entity_key_values = [];
        foreach ( $enitity_properties as $property_name => $property_value ) {
            if ( in_array( $property_name, $columns ) && !is_null( $property_value ) ) {
                $entity_key_values[ $property_name ] = $property_value;
            }
        }

        return $this->insert( $entity_key_values, true );
    }

    public function insert( array $key_values, $return_object )
    {
        $columns = [];
        $values = [];
        foreach ( $key_values as $column => $value ) {
            $columns[] = $column;
            $values[] = $value;
            $tokens_array[] = ":" . $column;
        }

        $this->validateColumns( $columns );

        $tokens = implode( ",", $tokens_array );
        // TODO Maybe column names should be encapsulated by tic marks
        $columns = implode( ",", $columns );
        $sql = $this->database->prepare( "INSERT INTO `{$this->getTable()}` ($columns) VALUES ($tokens)" );
        $token_index = 0;

        foreach ( $values as &$value ) {
            $sql->bindParam( $tokens_array[ $token_index ], $value );
            $token_index++;
        }

        $sql->execute();


        if ( $return_object ) {
            return $this->get( [ "*" ], [ "id" => $this->database->lastInsertId() ], "single" );
        }

        return $this->database->lastInsertId();
    }

    public function get( array $cols_to_get, array $key_values, $return_type )
    {
        $this->validateColsToGet( $cols_to_get );
        $this->validateReturnType( $return_type );

        $table = $this->getTable();
        $query = "SELECT ";
        $columns_query = $this->formatQueryColumns( $cols_to_get );
        $where_query = $this->formatQueryWhere( $key_values );

        $query = $query . $columns_query . " FROM " . "`" . $table . "`" . $where_query;

        $sql = $this->database->prepare( $query );

        // Bind parameters in where query. The token will the name of the key
        // preceded by a colon.
        foreach ( $key_values as $key => &$value ) {
            if ( $value !== null && $value !== "" ) {
                $token = ":" . $key;
                $sql->bindParam( $token, $value );
            }
        }

        $sql->execute();

        $entities = [];

        if ( $return_type == "raw" && $cols_to_get != "*" ) {
            $data = [];
            if ( count( $cols_to_get ) > 1 ) {
                while ( $response = $sql->fetch( \PDO::FETCH_ASSOC ) ) {
                    foreach ( $cols_to_get as $col ) {
                        $data[] = $response;
                    }
                }

                return $data;
            }

            while ( $response = $sql->fetch( \PDO::FETCH_ASSOC ) ) {
                $data[] = $response[ $cols_to_get[ 0 ] ];
            }

            return $data;
        }

        while ( $response = $sql->fetch( \PDO::FETCH_ASSOC ) ) {
            $entity = $this->build( $this->formatEntityNameFromTable() );
            $this->populate( $entity, $response );
            $entities[] = $entity;
        }

        switch ( $return_type ) {
            case "single":
            case "entity":
                if ( empty( $entities ) ) {
                    return null;
                }
                return $entities[ 0 ];
            case "array":
            case "entity-array":
                return $entities;
            case "json":
                return json_encode( $entities );
        }
    }

    public function update( array $columns_to_update, array $where_columns, $validate = true )
    {
        if ( $validate ) {
            $this->validateColumnsFromKeys( $columns_to_update );
            $this->validateColumnsFromKeys( $where_columns );
        }

        // NOTE We construct the value for the 'token' argument of PDO::bindParam()
        // by the name of the column being referenced prefixed by a colon. Ex: ":first_name"
        // Different token prefixes are used in this case to avoid overwriting the parameter
        // value when using the bindParam method when refenceing and updating the same
        // column. Consider the query below:
        //
        // 'UPDATE `user` SET first_name = :first_name WHERE first_name = :first_name'
        //
        // There are two ":first_name" tokens in the query. Without adding a prefix to the token name,
        // the values of each token will be equal the the last instance in which PDO::bindParam
        // was called.

        $query = "UPDATE " . "`" . $this->getTable() . "`" . " SET " . $this->formatQuerySet( $columns_to_update, "0" ) . $this->formatQueryWhere( $where_columns, "1" );

        $sql = $this->database->prepare( $query );

        foreach ( $columns_to_update as $key => &$value ) {
            if ( $value !== null && $value !== "" ) {
                $token = ":" . "0" . $key;
                $sql->bindParam( $token, $value );
            }
        }

        foreach ( $where_columns as $key => &$value ) {
            if ( $value !== null && $value !== "" ) {
                $token = ":" . "1" . $key;
                $sql->bindParam( $token, $value );
            }
        }

        $sql->execute();
    }

    public function delete( array $keys, array $values )
    {
        if ( empty( $keys ) || empty( $values ) ) {
            throw new \Exception( "Keys and values arrays connot be empty" );
        }

        if ( count( $keys ) != count( $values ) ) {
            throw new \Exception( "The number of keys and values does not match" );
        }
        $table = $this->getTable();
        $query = "DELETE FROM " . "`" . $table . "`" . $this->formatQueryWhereKeyValuePairs( $keys, $values );

        $sql = $this->database->prepare( $query );

        // Bind parameters in where query. The token will the name of the key
        // preceded by a colon.
        $key_values = array_combine( $keys, $values );
        foreach ( $key_values as $key => &$value ) {
            if ( $value !== null && $value !== "" ) {
                $token = ":" . $key;
                $sql->bindParam( $token, $value );
            }
        }

        $sql->execute();
    }

    public function build( $class_name )
    {
        return $this->entityFactory->build( $class_name );
    }

    protected function populate( $entity, $data )
    {
        if ( !is_object( $entity ) ) {
            throw new \Exception( "Argument 'entity' must be an object" );
        }

        if ( $data ) {
            foreach ( $data as $key => $d ) {
                $entity->{$key} = $data[ $key ];
            }
        }
    }

    public function setTable()
    {
        // Derive table name from mapper name
        $class_name = str_replace( "Mapper", "", str_replace( __NAMESPACE__ . "\\", "", get_class( $this ) ) );

        // Split the class name at the upper case letters
        $parts = preg_split( "/(?=[A-Z])/", lcfirst( $class_name ) );

        // Combine with underscores to make table name
        $table = implode( "_", $parts );

        $this->table = strtolower( $table );
    }

    public function getTable()
    {
        return $this->table;
    }

    protected function formatEntityNameFromTable()
    {
        $parts = explode( "_", $this->getTable() );
        $formatted_parts = [];

        foreach ( $parts as $part ) {
            $formatted_parts[] = ucfirst( $part );
        }

        return implode( "", $formatted_parts );
    }


    protected function formatQueryColumns( array $columns )
    {
        return implode( ", ", $columns );
    }

    private function formatQueryWhere( array $key_values, $token_prefix = "" )
    {
        $where_query = "";
        $total = count( $key_values );

        $iteration = 1;
        foreach ( $key_values as $key => $value ) {

            if ( $iteration == 1 && !empty( $key_values ) ) {
                $where_query = " WHERE ";
            }
            $and = "";
            if ( $iteration != $total ) {
                $and = " AND ";
            }

            if ( $value === null || $value === "" ) {
                $where_query = $where_query . "{$key} IS NULL" . $and;
                $iteration++;

                continue;
            }

            $where_query = $where_query . "{$key} = :{$token_prefix}{$key}" . $and;
            $iteration++;
        }
        
        return $where_query;
    }

    public function formatQuerySet( array $key_values, $token_prefix = "" )
    {
        $query = "";
        $total = count( $key_values );

        $i = 1;
        foreach ( $key_values as $key => $value ) {

            $and = "";
            if ( $i != $total ) {
                $and = ", ";
            }

            if ( $value === null || $value === "" ) {
                $query = $query . "{$key} = NULL" . $and;
                $i++;

                continue;
            }

            $query = $query . "{$key} = :{$token_prefix}{$key}" . $and;
            $i++;
        }

        return $query;
    }

    public function formatQueryWhereKeyValuePairs( array $keys, array $values )
    {
        $key_values = array_combine( $keys, $values );

        return $this->formatQueryWhere( $key_values );
    }

    private function getColumns()
    {
        $table = $this->getTable();
        $sql = $this->database->prepare( "SHOW COLUMNS FROM `{$table}`" );
        $sql->execute();

        $columns = [];

        while ( $response = $sql->fetch( \PDO::FETCH_ASSOC ) ) {
            $columns[] = $response[ "Field" ];
        }

        return $columns;
    }

    // Query execution
    public function execute( $query, array $token_map, $return_format )
    {
        $sql = $this->database->prepare( $query );

        foreach ( $token_map as $token => &$value ) {
            $sql->bindParam( $token, $value );
        }

        if ( $return_format === null ) {
            return $sql->execute();
        }

        $sql->execute();

        $data = [];

        while ( $response = $sql->fetch( \PDO::FETCH_ASSOC ) ) {
            $data[] = $response;
        }

        // Return the data from the transaction in the form specified by return_type
        if ( $return_format != "array" ) {
            $entities = [];
            foreach ( $data as $response ) {
                $entity = $this->build( $this->formatEntityNameFromTable() );
                $this->populate( $entity, $response );
                $entities[] = $entity;
            }

            switch ( $return_format ) {
                case "entity":
                    return ( empty( $entities ) ? null : $entities[ 0 ] );
                case "entity-array":
                    return $entities;
                case "json":
                    return json_encode( $entities );
                case "collection":
                    throw new \Exception( "The 'collection' return type has not yet been implemented" );
            }
        }

        return $data;
    }

    // Validations

    private function validateColsToGet( $cols_to_get )
    {
        if ( empty( $cols_to_get ) ) {
            throw new \Exception( "cols_to_get array connot be empty" );
        }

        return;
    }

    private function validateReturnType( $return_type )
    {
        if ( !in_array( $return_type, $this->validReturnTypes ) ) {
            throw new \Exception( "{$return_type} is not a valid return type" );

        }
    }

    // Ensure the columns actually exist in the database
    public function validateColumns( array $columns_to_validate )
    {
        $columns = $this->getColumns();

        foreach ( $columns_to_validate as $column ) {
            if ( !in_array( $column, $columns, true ) ) {
                throw new \Exception( "{$column} is not a valid column in table {$this->getTable()}" );
            }
        }

        return;
    }

    private function validateColumnsFromKeys( array $columns_to_validate )
    {
        $column_keys = [];

        foreach ( $columns_to_validate as $key => $value ) {
            $column_keys[] = $key;
        }

        $this->validateColumns( $column_keys );
    }
}
