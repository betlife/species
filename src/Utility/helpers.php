<?php

use Artisan\Utility\Request;
use Artisan\Utility\HigherOrderCallback;

/**
 * 
 * @param string $data
 * @return string
 */
if( !function_exists('escape') ) {

    /**
     * 
     * @param string $data
     * @return string
     */
    function escape($data)
    {
        return trim(htmlentities($data, ENT_QUOTES, 'utf-8'));
    }

}
if( !function_exists('env') ) {

    /**
     * 
     * @param type $key
     * @param type $default
     * @return boolean|string
     */
    function env($key, $default = null)
    {
        $value = getenv($key);
        if( $value === false ) {
            return get_value($default);
        }

        switch($value) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return "";
            case 'null':
            case '(null)':
                return;
        }

        if( ($valueLength = strlen($value)) > 1 && $value[0] === '"' && $value[$valueLength - 1] === '"' ) {
            return substr($value, 1, -1);
        }
        return $value;
    }

}
if( !function_exists('input') ) {

    /**
     * 
     * @param string $key
     * @param string $default
     * @return string
     */
    function input($key, $default = '')
    {
        return Request::get($key, $default);
    }

}
if( !function_exists('old') ) {

    /**
     * 
     * @param string $key
     * @return string
     */
    function old($key)
    {
        return Request::old($key);
    }

}
if( !function_exists('html') ) {

    /**
     * 
     * @param array $countable
     * @return array
     */
    function html(array $countable)
    {
        return array_map('escape', $countable);
    }

}

if( !function_exists('throw_if') ) {

    /**
     * Throws an exception if a given condition is true
     * 
     * @param mixed $condition
     * @param Throwable|string $exception
     * @param array ...$parameters
     * @return mixed
     * @throws Throwable
     */
    function throw_if($condition, $exception, ...$parameters)
    {
        if( $condition ) {
            throw (is_string($exception)) ? new $exception(...$parameters) : $exception;
        }

        return $condition;
    }

}

if( !function_exists('throw_unless') ) {

    /**
     * Throws an exception unless a given condition is true
     * 
     * @param mixed $condition
     * @param Throwable|string $exception
     * @param array  ...$parameters
     * @return mixed
     * @throws Throwable
     */
    function throw_unless($condition, $exception, ...$parameters)
    {
        if( !$condition ) {
            throw (is_string($exception)) ? new $exception(...$parameters) : $exception;
        }

        return $condition;
    }

}

if( !function_exists('tap') ) {

    /**
     * 
     * @param type $value
     * @param callable|null $callback
     * @return HigherOrderCallback
     */
    function tap($value, callable $callback = null)
    {
        if( is_null($callback) ) {
            return new HigherOrderCallback($value);
        }

        $callback($value);
        return $value;
    }

}

if( !function_exists('with') ) {

    /**
     * 
     * @param type $value
     * @param callable|null $callback
     * @return type
     */
    function with($value, callable $callback = null)
    {
        return is_null($callback) ? $value : $callback($value);
    }

}

if( !function_exists('get_value') ) {

    /**
     * Returns the default value as passed
     * 
     * @param mixed $value
     * @return mixed
     */
    function get_value($value)
    {
        return is_callable($value) ? $value() : $value;
    }

}

if( !function_exists('windows_os') ) {

    /**
     * Determines if the current environment is windows based
     * 
     * @return bool
     */
    function windows_os()
    {
        return strtolower(substr(PHP_OS, 0, 3)) === 'win';
    }

}
if( !function_exists('object') ) {

    /**
     * 
     * @param type $parameter
     * @return type
     */
    function object($parameter)
    {
        return is_object($parameter);
    }

}
if( !function_exists('assign_return') ) {

    /**
     * 
     * @param mixed $value
     * @param bool $condtion
     * @param mixed $assign
     * @return mixed
     */
    function assign_return($value, $condtion, $assign)
    {
        if( $condtion ) {
            $value = $assign;
        }
        return $value;
    }

}

// Beginnig of file helper functions
if( !function_exists('format_pathname') ) {

    /**
     * Formats a pathname based on platform(supporting OS)
     * 
     * @param string $filename
     * @param string $pathname
     * @return string
     */
    function format_pathname($pathname)
    {
        return preg_replace('/[\\\\ \/]/', DIRECTORY_SEPARATOR, $pathname);
    }

}

if( !function_exists('merge_filename') ) {

    /**
     * Merges a filename to a pathname
     * 
     * @param string $filename
     * @param string $pathname
     * @return string
     */
    function merge_filename($filename, $pathname)
    {
        $filename = (array) $filename;
        array_unshift($filename, rtrim(format_pathname($pathname), DIRECTORY_SEPARATOR));

        return implode($filename, DIRECTORY_SEPARATOR);
    }

}

if( !function_exists('file_close') ) {

    /**
     * 
     * @param resource $handle
     * @return boolean|void
     */
    function file_close(&$handle)
    {
        if( is_resource($handle) ) {
            fclose($handle);
            return true;
        }
        return;
    }

}

if( !function_exists('file_unlock_and_close') ) {

    /**
     * 
     * @param resource $handle
     * @return boolean|void
     */
    function file_unlock_and_close(&$handle)
    {
        if( is_resource($handle) ) {
            flock($handle, LOCK_UN);
            fclose($handle);
            $handle = null;
            return true;
        }
        return;
    }

}

function debug($expression)
{
    echo '<pre>';
    var_dump($expression);
    echo '</pre>';
}
