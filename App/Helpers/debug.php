<?php
    function dd( $input = "" ) {
        $backtrace = debug_backtrace(); 

        eb( "teminated @ line " . $backtrace[ 0 ][ "line" ] . " in file " . $backtrace[ 0 ][ "file" ] );
        vdd( $input );
    }

    function ppd( $input = "" ) {
        echo "<pre>";
        print_r( $input );
        echo "</pre>";
        die();
    }

    function pp( $input = "" ) {
        echo "<pre>";
        print_r( $input );
        echo "</pre>";
    }

    function vd( $input = "" ) {
        echo "<pre>";
        var_dump( $input );
        echo "</pre>";
    }

    function vdd( $input = "" ) {
        vd( $input );
        die();
        exit();
    }

    function ed( $input = "" ) {
        echo $input;
        die();
        exit();
    }

    function en( $input ) {
        echo( $input );
        echo( PHP_EOL );
    }

    function eb( $input = "" )
    {
        en( $input );
        echo( "<br>" );
    }

    function bs()
    {
        return microtime( true );
    }

    function be( $start_time, $label = "" )
    {
        eb( $label . ( microtime( true ) - $start_time ) );
    }

    class Benchmark
    {
        private static $benchmarks = [];

        public static function start( $index, $description = null )
        {
            if ( !array_key_exists( $index , static::$benchmarks ) ) {
                static::$benchmarks[ $index ][ "start" ] = microtime( true );
                static::$benchmarks[ $index ][ "end" ] = null;
                static::$benchmarks[ $index ][ "time" ] = null;
                static::$benchmarks[ $index ][ "description" ] = $description;
                $backtrace = debug_backtrace();
                static::$benchmarks[ $index ][ "start-call-file" ] = "Called from line " . $backtrace[ 0 ][ "line" ] . " in file " . $backtrace[ 0 ][ "file" ];
                static::$benchmarks[ $index ][ "end-call-file" ] = null;

                return;
            }

            throw new \Exception( "Benchmark Index already exists." );
        }

        public static function end( $index )
        {
            if ( array_key_exists( $index, static::$benchmarks ) ) {
                if ( is_null( static::$benchmarks[ $index ][ "end" ] ) ) {
                    static::$benchmarks[ $index ][ "end" ] = microtime( true );
                    $backtrace = debug_backtrace();
                    static::$benchmarks[ $index ][ "end-call-file" ] = "Called from line " . $backtrace[ 0 ][ "line" ] . " in file " . $backtrace[ 0 ][ "file" ];
                    static::$benchmarks[ $index ][ "time" ] = static::calcEndTime( $index );
                    
                    return;
                }

                throw new \Exception( "Benchmark already ended" );
            }

            throw new \Exception( "Benchmark does not exist for that index" );
        }

        private static function calcEndTime( $index )
        {
            return static::$benchmarks[ $index ][ "end" ] - static::$benchmarks[ $index ][ "start" ];
        }

        public static function showAll()
        {
            pp( static::$benchmarks );
            return;
        }

        public static function description( $index, $description )
        {
            if ( array_key_exists( $index , static::$benchmarks ) ) {
                static::$benchmarks[ $index ][ "description" ] = $description;

                return;
            }

            throw new \Exception( "Benchmark index '{$index}' does not exists." );
        }

        public static function sum( array $indicies )
        {
            $total = 0;

            foreach ( $indicies as $index ) {
                if ( array_key_exists( $index, static::$benchmarks ) ) {
                    if ( !is_null( static::$benchmarks[ $index ] ) ) {
                        $total += static::$benchmarks[ $index ][ "time" ];
                        continue;
                    }

                    throw new \Exception( "Time at benchmark index '{$index}' has not yet been calculated" );
                }
                
                throw new \Exception( "Benchmark index '$index' does not exist" );
            }
            
            return $total;

        }
    }

