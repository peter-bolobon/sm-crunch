<?php

namespace SmCrunch;

use Exception;

/**
 * Provides access to the specified .env file values.
 */
class Env {

    protected string $path;

    protected ?array $variables = null;

    public function __construct( string $path ) {
        $this->path = $path;
    }

    /**
     * Returns a value in the .env file identified by key, or the optional default value.
     *
     * @param string $key
     * @param null $default
     *
     * @return mixed|null
     * @throws Exception
     */
    public function get( string $key, $default = null ) {
        if ( $this->variables === null ) {
            $this->variables = $this->readEnv();
        }

        if ( !isset( $this->variables[$key] ) ) {
            return $default;
        }

        return $this->variables[$key];
    }

    /**
     * Reads the .env file and loads values into memory.
     *
     * @return array
     * @throws Exception
     */
    protected function readEnv(): array {
        if ( !is_readable( $this->path ) ) {
            throw new Exception( 'Environment file not found or not readable' );
        }

        $body = file_get_contents( $this->path );

        // Convert line breaks.
        $body = str_replace( [ "\r\n", "\r", "\n" ], "\n", $body );

        $lines = explode( "\n", $body );
        $variables = [];
        foreach ( $lines as $line ) {
            if ( trim( $line ) === '' ) {
                continue;
            }

            [ $key, $value ] = explode( '=', $line, 2 );
            $variables[trim( $key )] = trim( $value );
        }

        return $variables;
    }

}
