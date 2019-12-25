<?php

namespace Artisan\Http;

/**
 * Description of Environment
 *
 * @author Cloud
 */
use Artisan\Utility\Hash;
use Artisan\Core\Collection\Collection;

class Environment extends Collection
{

    /**
     * Gathers environment environment variables.
     * Environment variables are most likely to be collected from $_SERVER
     * 
     * @param array $userData
     * @return \Artisan\Http\Environment
     */
    public static function create($userData = [])
    {
        if( (isset($userData['HTTPS']) && $userData['HTTPS'] != 'off') ||
                (isset($userData['REQUEST_SCHEME']) && $userData['REQUEST_SCHEME'] == 'https') ) {
            $defaultScheme = 'https';
            $defaultPort   = 433;
        }
        else {
            $defaultPort   = 80;
            $defaultScheme = 'http';
        }

        $data = Hash::combine([
                    'SCRIPT_NAME'        => '',
                    'REQUEST_URI'        => '',
                    'QUERY_STRING'       => '',
                    'SERVER_NAME'        => 'localhost',
                    'SERVER_PORT'        => $defaultPort,
                    'REQUEST_SCHEME'     => $defaultScheme,
                    'REQUEST_METHOD'     => 'GET',
                    'REQUEST_PROTOCOL'   => 'HTTP/1.1',
                    'REMOTE_ADDR'        => '127.0.0.1',
                    'REQUEST_TIME'       => time(),
                    'REQUEST_TIME_FLOAT' => microtime(true)
                        ], $userData);

        return new static($data);
    }

}
